<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PracticeSchedule extends Model
{
    use HasUuids;

    protected $fillable = [
        'day_available_id', 
        'Datetime',         
    ];

    protected $casts = [
        'Datetime' => 'datetime',
    ];

    public function dayAvailable()
    {
        // Jika PracticeSchedule dibuat berdasarkan DayAvailable,
        // ini akan menjadi relasi belongsTo.
        // Pastikan ada kolom 'day_available_id' di tabel practice_schedules
        return $this->belongsTo(DayAvailable::class);
    }

    public function appointment()
    {
        // Satu PracticeSchedule hanya bisa memiliki SATU Appointment (booking)
        return $this->hasOne(Appointment::class, 'schedule_id');
    }

    public function doctor()
    {
        // Satu PracticeSchedule dimiliki oleh satu dokter
        return $this->belongsTo(Doctor::class);
    }
}