<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    use HasUuids;

    protected $fillable = [
        'lab_result_number',
        'patient_id',
        'test_type',
        'test_date',
        'result_date',
        'comments',
        'price',
    ];

    protected $casts = [
        'test_date' => 'datetime',
        'result_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
