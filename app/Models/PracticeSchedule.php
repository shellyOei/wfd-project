<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class PracticeSchedule extends Model
{
    use HasUuids;

    protected $fillable = [
        'Datetime',
    ];

    public function dayAvailable()
    {
        return $this->belongsTo(DayAvailable::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'schedule_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'schedule_id');
    }

    public function doctor() 
    {
        return $this->belongsTo(Doctor::class);
    }
}
