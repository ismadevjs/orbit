<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYCRequest extends Model
{
    use HasFactory;

    protected $table = 'kyc_requests';
    protected $fillable = [
        'user_id',
        'document_type',
        'selfie_path',
        'front_photo_path',
        'back_photo_path',
        'passport_photo_path',
        'additional_info',
        'status',
        'residency_photo_path',
        'license_front_photo_path',
        'license_back_photo_path',
        'is_signed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
