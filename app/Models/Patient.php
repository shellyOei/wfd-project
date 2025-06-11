<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasUuids;

    protected $fillable = [
        'id', 
        'patient_number',
        'name',
        'phone',
        'sex',
        'date_of_birth',
        'address',
        'occupation',
        'blood_type',
        'rhesus_factor',
        'id_card_number',
        'BPJS_number',
    ];

    protected $hidden = [
        'id_card_number',
        'BPJS_number',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($patient) {
            // Generate a unique patient number.
            // Loop until a truly unique number is found to avoid collisions.
            do {
                $patientNumber = rand(100000, 999999);
            } while (self::where('patient_number', $patientNumber)->exists());
            $patient->patient_number = $patientNumber;
        });
    }

    public function profiles()
    {
        return $this->belongsToMany(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }
}
