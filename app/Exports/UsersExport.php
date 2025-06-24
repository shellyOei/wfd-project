<?php

namespace App\Exports;

use App\Models\User;
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

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with(['patients', 'profiles'])
                  ->withTrashed()
                  ->orderBy('created_at', 'desc')
                  ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'User ID',
            'Name',
            'Email',
            'Linked Patients',
            'Patient Names',
            'Total Linked Patients',
            'Account Status',
            'Registration Date',
            'Last Updated',
            'Deleted Date'
        ];
    }

    /**
     * @param User $user
     * @return array
     */
    public function map($user): array
    {
        // Get linked patients information
        $linkedPatients = $user->patients;
        $patientNames = $linkedPatients->pluck('name')->implode(', ');
        $totalLinkedPatients = $linkedPatients->count();
        
        // If no patients linked, show appropriate message
        if ($totalLinkedPatients === 0) {
            $patientNames = 'No patients linked';
        }
        
        // Determine account status
        $accountStatus = $user->deleted_at ? 'Deactivated' : 'Active';
        
        return [
            $user->id,
            $user->name,
            $user->email,
            $totalLinkedPatients > 0 ? 'Yes' : 'No',
            $patientNames,
            $totalLinkedPatients,
            $accountStatus,
            $user->created_at->format('Y-m-d H:i:s'),
            $user->updated_at->format('Y-m-d H:i:s'),
            $user->deleted_at ? $user->deleted_at->format('Y-m-d H:i:s') : 'N/A'
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
                    'startColor' => ['rgb' => '059669']
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
            
            // Data rows styling
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
            'A' => 36, // User ID
            'B' => 25, // Name
            'C' => 30, // Email
            'D' => 15, // Linked Patients
            'E' => 40, // Patient Names
            'F' => 20, // Total Linked Patients
            'G' => 15, // Account Status
            'H' => 18, // Registration Date
            'I' => 18, // Last Updated
            'J' => 18  // Deleted Date
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Users Data';
    }
} 