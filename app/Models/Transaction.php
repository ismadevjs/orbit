<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_method',
        'transaction_reference',
        'amount',
        'currency',
        'status',
        'details',
        'description',
        'processed_at',
        'payment_account',
        'payment_gateway_fee',
        'is_test',
        'ip_address',
    ];

    protected $casts = [
        'details' => 'array',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   
}
