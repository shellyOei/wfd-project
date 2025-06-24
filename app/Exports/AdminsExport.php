<?php

namespace App\Exports;

use App\Models\Admin;
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

class AdminsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Admin::with(['doctor.specialization'])
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
            'Admin ID',
            'Name',
            'Email',
            'Role Type',
            'Connected Doctor',
            'Doctor Specialization',
            'Doctor Phone',
            'Account Status',
            'Created Date',
            'Last Updated',
            'Deleted Date'
        ];
    }

    /**
     * @param Admin $admin
     * @return array
     */
    public function map($admin): array
    {
        // Determine role type
        $roleType = $admin->isSuperAdmin() ? 'Super Admin' : 'Doctor Admin';
        
        // Get doctor information
        $doctorName = $admin->doctor ? $admin->doctor->name : 'Not Connected';
        $doctorSpecialization = $admin->doctor && $admin->doctor->specialization 
            ? $admin->doctor->specialization->name 
            : 'N/A';
        $doctorPhone = $admin->doctor ? $admin->doctor->phone : 'N/A';
        
        // Determine account status
        $accountStatus = $admin->deleted_at ? 'Deactivated' : 'Active';
        
        return [
            $admin->id,
            $admin->name,
            $admin->email,
            $roleType,
            $doctorName,
            $doctorSpecialization,
            $doctorPhone,
            $accountStatus,
            $admin->created_at->format('Y-m-d H:i:s'),
            $admin->updated_at->format('Y-m-d H:i:s'),
            $admin->deleted_at ? $admin->deleted_at->format('Y-m-d H:i:s') : 'N/A'
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
                    'startColor' => ['rgb' => 'DC2626']
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
            'A' => 36, // Admin ID
            'B' => 25, // Name
            'C' => 30, // Email
            'D' => 15, // Role Type
            'E' => 25, // Connected Doctor
            'F' => 20, // Doctor Specialization
            'G' => 15, // Doctor Phone
            'H' => 15, // Account Status
            'I' => 18, // Created Date
            'J' => 18, // Last Updated
            'K' => 18  // Deleted Date
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Admins Data';
    }
} 