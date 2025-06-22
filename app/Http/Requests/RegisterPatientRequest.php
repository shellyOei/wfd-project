<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterPatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow all users to make this request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $patientId = $this->route('patient');

        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|min:10|max:20',
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'id_card_number' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('patients', 'id_card_number')->ignore($patientId),
            ],
            'emergency_contact' => 'nullable|string|max:255',
            'allergy' => 'nullable|string|max:500',
            'occupation' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'blood_type' => 'required|in:A,B,AB,O,Belum tahu',
            'rhesus_factor' => 'required|in:Positif,Negatif,Belum tahu',
            'BPJS_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('patients', 'BPJS_number')->ignore($patientId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap tidak boleh lebih dari 255 karakter.',

            'phone.required' => 'Nomor telepon wajib diisi.',
            'phone.string' => 'Nomor telepon harus berupa teks.',
            'phone.min' => 'Nomor telepon harus terdiri dari minimal 10 karakter.',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter.',

            'sex.required' => 'Jenis kelamin wajib diisi.',
            'sex.in' => 'Jenis kelamin tidak valid. Pilih antara Laki-laki atau Perempuan.', // Adjusted for 'Male', 'Female'

            'date_of_birth.required' => 'Tanggal lahir wajib diisi.',
            'date_of_birth.date' => 'Tanggal lahir harus dalam format tanggal yang valid.',
            'date_of_birth.before' => 'Tanggal lahir harus sebelum hari ini.',

            'id_card_number.required' => 'Nomor KTP wajib diisi.',
            'id_card_number.string' => 'Nomor KTP harus berupa teks.',
            'id_card_number.digits' => 'Nomor KTP harus terdiri dari 16 digit.',
            'id_card_number.unique' => 'Nomor KTP sudah terdaftar.',

            'emergency_contact.string' => 'Kontak darurat harus berupa teks.',
            'emergency_contact.max' => 'Kontak darurat tidak boleh lebih dari 255 karakter.',
            
            'allergy.string' => 'Alergi harus berupa teks.',
            'allergy.max' => 'Alergi tidak boleh lebih dari 500 karakter.',
            
            'occupation.required' => 'Profesi wajib diisi.',
            'occupation.string' => 'Profesi harus berupa teks.',
            'occupation.max' => 'Profesi tidak boleh lebih dari 255 karakter.',

            'address.required' => 'Alamat tempat tinggal wajib diisi.',
            'address.string' => 'Alamat tempat tinggal harus berupa teks.',
            'address.max' => 'Alamat tempat tinggal tidak boleh lebih dari 500 karakter.',

            'blood_type.required' => 'Golongan darah wajib diisi.',
            'blood_type.in' => 'Golongan darah tidak valid. Pilihan yang tersedia: A, B, AB, O.',

            'rhesus_factor.required' => 'Rhesus wajib diisi.',
            'rhesus_factor.in' => 'Rhesus tidak valid. Pilihan yang tersedia: Positif, Negatif.', // Adjusted for 'rhesus_factor'

            'BPJS_number.string' => 'Nomor BPJS harus berupa teks.',
            'BPJS_number.max' => 'Nomor BPJS tidak boleh lebih dari 20 karakter.',
            'BPJS_number.unique' => 'Nomor BPJS sudah terdaftar.',
        ];
    }
}
