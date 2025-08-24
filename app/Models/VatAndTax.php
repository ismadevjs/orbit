<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatAndTax extends Model
{
    use HasFactory;

    protected $table = 'vat_taxes';

    protected $fillable = [
        'name',
        'rate',
        'type',
        'description',
        'is_active'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean'
    ];
}
