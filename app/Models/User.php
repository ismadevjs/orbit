<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'phone',
        'avatar',
        'country',
        'gender',
        'affiliate_id',
        'nin',
        'passport',
        'id_card_recto',
        'id_card_verso',
        'residence',
        'is_signed',
        'whatsapp',
        'occupation',
        'date_of_birth', 
        'address',       
        'postal_code', 
        'otp',
        'otp_expires_at',   
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'affiliate_id', 'id');
    }


    public function referralLinks()
    {
        return $this->hasMany(ReferralLink::class);
    }

    public function kycRequest()
    {
        return $this->hasOne(KYCRequest::class, 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }


    public function contract()
        {
            return $this->hasOne(ContractList::class)->latestOfMany();
        }


    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function investor()
    {
        return $this->hasOne(Investor::class);
    }

    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }

    public function sessions()
    {
        return $this->hasMany(Session::class, 'user_id');
    }
}
