<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasUuids;
    
    public $fillable = [
        'id',
        'doctor_number',
        'name',
        'front_title',
        'back_title',
        'phone',
        'address',
        'photo',
    ];

    public function specialization()
    {
        return $this->hasOnea(Specialization::class);
    }

    public function schedules()
    {
        return $this->hasMany(PracticeSchedule::class);
    }
}
