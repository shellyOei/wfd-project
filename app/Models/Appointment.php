<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasUuids;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'queue_number',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'type',
        'is_bpjs',
        'notes',
    ];

    protected $casts = [
        'is_bpjs' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function schedule()
    {
        return $this->belongsTo(PracticeSchedule::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
