<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasUuids;

    protected $fillable = [
        'invoice_number',
        'payment_method',
        'is_bpjs',
        'total_price',
    ];

    protected $casts = [
        'is_bpjs' => 'boolean',
        'total_price' => 'decimal:2',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
