<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasUuids;

    protected $fillable = [
        'medicine_id',
        'appointment_id',
        'quantity',
        'quantity_bought',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'quantity_bought' => 'integer',
    ];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
