<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use HasUuids;

    protected $fillable = [
        'appointment_number',
        'patient_id',
        'schedule_id',
        'queue_number',
        'subjective',
        'objective',
        'assessment',
        'plan',
        'type',
        'is_bpjs',
        'notes',
        'status', // 1=confirmed, 2=Cancelled, 3=Completed
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            $appointment->id = (string) Str::uuid();

            // Generate appointment number like: APPT-20250622-001
            $datePrefix = now()->format('Ymd'); 
            $last = self::whereDate('created_at', now()->toDateString())
                ->orderBy('created_at', 'desc')
                ->first();

            $nextNumber = 1;

            if ($last && isset($last->appointment_number)) {
                $parts = explode('-', $last->appointment_number);
                if (isset($parts[2])) {
                    $nextNumber = (int)$parts[2] + 1;
                }
            }

            $appointment->appointment_number = 'APPT-' . $datePrefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    protected $casts = [
        'is_bpjs' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function schedule()
    {
        return $this->belongsTo(PracticeSchedule::class, 'schedule_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
