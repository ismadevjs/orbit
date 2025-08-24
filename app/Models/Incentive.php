<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    use HasFactory;

    protected $table = 'incentives';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'affiliate_stage_id',
        'percentage',
        'can_invite',
        'bonus_type',
        'from_date',
        'to_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
    ];

    /**
     * Get the affiliate stage that owns the incentive.
     */
    public function affiliateStage()
    {
        return $this->belongsTo(AffiliateStage::class);
    }

    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'incentive_investor');
    }
}
