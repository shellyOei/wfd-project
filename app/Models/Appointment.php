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
            // Set the UUID if it's not already set
            if (empty($appointment->id)) {
                $appointment->id = (string) Str::uuid();
            }

            // The save() method in Laravel automatically wraps this in a transaction.
            // We just need to ensure our read operation within that transaction is locked.

            $datePrefix = now()->format('Ymd');
            $prefix = 'APPT-' . $datePrefix;

            // 1. Lock the latest record matching today's prefix.
            //    This forces other requests to wait until the current transaction is finished.
            // 2. We sort by the appointment_number itself for maximum reliability.
            $last = self::where('appointment_number', 'LIKE', $prefix . '%')
                ->orderBy('appointment_number', 'desc')
                ->lockForUpdate() // This is the crucial lock
                ->first();

            $nextNumber = 1;
            if ($last) {
                // Extract the numeric part from the last appointment number and increment it.
                $parts = explode('-', $last->appointment_number);
                $nextNumber = (int)end($parts) + 1;
            }

            // Pad the number with leading zeros and assign it.
            $appointment->appointment_number = $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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
