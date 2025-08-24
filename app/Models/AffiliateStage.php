<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class AffiliateStage extends Model
{
    use HasFactory;

    protected $table = 'affiliate_stages';

    protected $fillable = [
        'name',
        'description',
        'duration',
        'capital',
        'team_size',
        'people_per_six_months',
        'role_id',
        'contract_id',
        'commission_percentage',
        'monthly_profit_less_50k',
        'monthly_profit_more_50k',
    ];

    protected $casts = [
        'duration' => 'integer',
        'capital' => 'double',
        'team_size' => 'integer',
        'people_per_six_months' => 'integer',
    ];

    /**
     * Get the incentives associated with the affiliate stage.
     */
    public function incentives()
    {
        return $this->hasMany(Incentive::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id');
    }

    public function latestActiveContract()
    {
        return $this->belongsTo(Contract::class, 'contract_id', 'id')
                ->where('status', 'ACTIVE')
                ->latest();
    }

}
