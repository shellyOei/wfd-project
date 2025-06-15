<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasUuids;

    protected $fillable = [
        'name',
        'email',
        'password',
        'doctor_id',
    ];

    protected $hidden = [
        'password',
    ];

     protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    // for role checking 
    public function isDoctor() : bool
    {
        return $this->doctor()->exists();
    }

     // Untuk pengecekan peran
    public function isDoctorAdmin(): bool
    {
        return $this->doctor_id !== null;
    }

    public function isSuperAdmin(): bool
    {
        return $this->doctor_id === null;
    }
}
