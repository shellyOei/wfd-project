<?php

namespace App\Exports;

use App\Models\Patient;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PatientsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Patient::with(['profiles.user', 'appointments'])
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Patient Number',
            'Full Name',
            'Phone Number',
            'Gender',
            'Date of Birth',
            'Age',
            'Blood Type',
            'Rhesus Factor',
            'Allergy',
            'Occupation',
            'Address',
            'ID Card Number',
            'BPJS Number',
            'Emergency Contact',
            'Linked Users',
            'Total Appointments',
            'Status',
            'Registration Date'
        ];
    }

    /**
     * @param Patient $patient
     * @return array
     */
    public function map($patient): array
    {
        // Calculate age
        $age = \Carbon\Carbon::parse($patient->date_of_birth)->age;
        
        // Get linked users
        $linkedUsers = $patient->profiles->pluck('user.email')->filter()->implode(', ');
        if (empty($linkedUsers)) {
            $linkedUsers = 'No linked users';
        }
        
        // Calculate total appointments
        $totalAppointments = $patient->appointments->count();
        
        // Determine status based on recent appointments
        $hasRecentAppointment = $patient->appointments->where('created_at', '>=', now()->subMonths(6))->count() > 0;
        $status = $hasRecentAppointment ? 'Active' : 'Inactive';
        
        return [
            $patient->patient_number,
            $patient->name,
            $patient->phone,
            ucfirst($patient->sex),
            \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d'),
            $age . ' years',
            $patient->blood_type ?? 'N/A',
            $patient->rhesus_factor ?? 'N/A',
            $patient->allergy ?? 'No known allergies',
            $patient->occupation,
            $patient->address,
            $patient->id_card_number,
            $patient->BPJS_number ?? 'Not registered',
            $patient->emergency_contact ?? 'Not provided',
            $linkedUsers,
            $totalAppointments,
            $status,
            $patient->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Get the highest row and column
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            
            // All data styling
            "A1:{$highestColumn}{$highestRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ],
            
            // Data rows styling (alternating colors)
            "A2:{$highestColumn}{$highestRow}" => [
                'font' => [
                    'size' => 10
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Patient Number
            'B' => 25, // Full Name
            'C' => 15, // Phone Number
            'D' => 10, // Gender
            'E' => 12, // Date of Birth
            'F' => 8,  // Age
            'G' => 12, // Blood Type
            'H' => 12, // Rhesus Factor
            'I' => 25, // Allergy
            'J' => 20, // Occupation
            'K' => 30, // Address
            'L' => 18, // ID Card Number
            'M' => 18, // BPJS Number
            'N' => 20, // Emergency Contact
            'O' => 25, // Linked Users
            'P' => 12, // Total Appointments
            'Q' => 10, // Status
            'R' => 18  // Registration Date
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Patients Data';
    }
}
