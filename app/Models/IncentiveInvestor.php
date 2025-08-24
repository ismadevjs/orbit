<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncentiveInvestor extends Model
{
    protected $table = 'incentive_investor';

    protected $fillable = [
        'investor_id',
        'incentive_id',
    ];

    /**
     * Get the investor that owns the incentive_investor.
     */
    public function investor()
    {
        return $this->belongsTo(Investor::class);
    }

    /**
     * Get the incentive that owns the incentive_investor.
     */
    public function incentive()
    {
        return $this->belongsTo(Incentive::class);
    }
}
