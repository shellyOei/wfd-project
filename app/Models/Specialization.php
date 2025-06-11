<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    //
    use HasUuids;
    
    protected $fillable = [
        'id',
        'name',
        'icon',
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
