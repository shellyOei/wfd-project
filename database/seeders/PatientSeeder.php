<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = [
            [
                'patient_number' => 'P001',
                'name' => 'John Doe',
                'phone' => '081234567890',
                'sex' => 'male',
                'date_of_birth' => '1990-05-15',
                'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'occupation' => 'Software Engineer',
                'blood_type' => 'A',
                'rhesus_factor' => '+',
                'id_card_number' => '3171051505900001',
                'BPJS_number' => '0001234567890',
            ],
            [
                'patient_number' => 'P002',
                'name' => 'Jane Smith',
                'phone' => '081234567891',
                'sex' => 'female',
                'date_of_birth' => '1985-08-22',
                'address' => 'Jl. Sudirman No. 456, Jakarta Selatan',
                'occupation' => 'Teacher',
                'blood_type' => 'B',
                'rhesus_factor' => '+',
                'id_card_number' => '3171052208850002',
                'BPJS_number' => '0001234567891',
            ],
            [
                'patient_number' => 'P003',
                'name' => 'Michael Johnson',
                'phone' => '081234567892',
                'sex' => 'male',
                'date_of_birth' => '1992-12-10',
                'address' => 'Jl. Thamrin No. 789, Jakarta Pusat',
                'occupation' => 'Marketing Manager',
                'blood_type' => 'O',
                'rhesus_factor' => '+',
                'id_card_number' => '3171051012920003',
                'BPJS_number' => null,
            ],
            [
                'patient_number' => 'P004',
                'name' => 'Sarah Wilson',
                'phone' => '081234567893',
                'sex' => 'female',
                'date_of_birth' => '1988-03-18',
                'address' => 'Jl. Gatot Subroto No. 321, Jakarta Selatan',
                'occupation' => 'Nurse',
                'blood_type' => 'AB',
                'rhesus_factor' => '+',
                'id_card_number' => '3171051803880004',
                'BPJS_number' => '0001234567893',
            ],
            [
                'patient_number' => 'P005',
                'name' => 'Robert Brown',
                'phone' => '081234567894',
                'sex' => 'male',
                'date_of_birth' => '1995-07-25',
                'address' => 'Jl. Kuningan No. 654, Jakarta Selatan',
                'occupation' => 'Graphic Designer',
                'blood_type' => 'A',
                'rhesus_factor' => '-',
                'id_card_number' => '3171052507950005',
                'BPJS_number' => null,
            ],
            [
                'patient_number' => 'P006',
                'name' => 'Emily Davis',
                'phone' => '081234567895',
                'sex' => 'female',
                'date_of_birth' => '1991-11-08',
                'address' => 'Jl. Kemang No. 987, Jakarta Selatan',
                'occupation' => 'Accountant',
                'blood_type' => 'B',
                'rhesus_factor' => '-',
                'id_card_number' => '3171050811910006',
                'BPJS_number' => '0001234567895',
            ],
            [
                'patient_number' => 'P007',
                'name' => 'David Miller',
                'phone' => '081234567896',
                'sex' => 'male',
                'date_of_birth' => '1987-04-14',
                'address' => 'Jl. Senayan No. 147, Jakarta Pusat',
                'occupation' => 'Civil Engineer',
                'blood_type' => 'O',
                'rhesus_factor' => '-',
                'id_card_number' => '3171051404870007',
                'BPJS_number' => null,
            ],
            [
                'patient_number' => 'P008',
                'name' => 'Lisa Anderson',
                'phone' => '081234567897',
                'sex' => 'female',
                'date_of_birth' => '1993-09-30',
                'address' => 'Jl. Menteng No. 258, Jakarta Pusat',
                'occupation' => 'Pharmacist',
                'blood_type' => 'AB',
                'rhesus_factor' => '-',
                'id_card_number' => '3171053009930008',
                'BPJS_number' => '0001234567897',
            ],
            [
                'patient_number' => 'P009',
                'name' => 'James Taylor',
                'phone' => '081234567898',
                'sex' => 'male',
                'date_of_birth' => '1989-01-12',
                'address' => 'Jl. Cikini No. 369, Jakarta Pusat',
                'occupation' => 'Lawyer',
                'blood_type' => 'A',
                'rhesus_factor' => '+',
                'id_card_number' => '3171051201890009',
                'BPJS_number' => '0001234567898',
            ],
            [
                'patient_number' => 'P010',
                'name' => 'Maria Garcia',
                'phone' => '081234567899',
                'sex' => 'female',
                'date_of_birth' => '1994-06-20',
                'address' => 'Jl. Blok M No. 741, Jakarta Selatan',
                'occupation' => 'Architect',
                'blood_type' => 'B',
                'rhesus_factor' => '+',
                'id_card_number' => '3171052006940010',
                'BPJS_number' => null,
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
