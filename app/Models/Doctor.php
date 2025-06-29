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
        'description',
        'specialization_id',
    ];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }
    
    public function dayAvailables()
    {
        return $this->hasMany(DayAvailable::class);
    }
    
}
