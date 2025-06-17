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
        return $this->belongsTo(DayAvailable::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'schedule_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}