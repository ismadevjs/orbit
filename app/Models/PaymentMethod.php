<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider',
        'image',
        'time',
        'tax',
        'min',
        'max',
        'status',
        'type',

    ];

    protected $casts = [
        'data' => 'array',
        'fields' => 'array',
    ];
}
