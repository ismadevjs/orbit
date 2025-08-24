<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'capital',
        'bonus',
        'profit',
        'pending_capital',
        'pending_bonus',
        'pending_profit',
        'is_locked',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get total balance
    public function getTotalBalanceAttribute()
    {
        return $this->capital + $this->bonus + $this->profit;
    }

    // Check if wallet is locked
    public function isLocked()
    {
        return $this->is_locked;
    }

}
