<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'unit',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
