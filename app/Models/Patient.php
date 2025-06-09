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
        // 'id_card_number',
        // 'BPJS_number',
    ];

    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }
     public function users()
    {
        return $this->belongsToMany(User::class, 'profiles', 'patient_id', 'user_id');
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
