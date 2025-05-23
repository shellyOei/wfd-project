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
    ];

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }
}
