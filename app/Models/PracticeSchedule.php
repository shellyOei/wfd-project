<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PracticeSchedule extends Model
{
    use HasUuids;

    protected $fillable = [
        'doctor_id',        // PENTING: FK ke tabel doctors
        'day_available_id', // PENTING: FK ke tabel day_availables (jika ingin melacak sumber pola)
        'Datetime',         // Tanggal dan waktu spesifik (misal: 2024-06-17 09:00:00)
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