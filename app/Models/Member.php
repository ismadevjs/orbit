<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'position',
        'experience',
        'location',
        'practice_area',
        'projects_done',
        'title',
        'description',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'image'
    ];

    protected $casts = ['social_medias' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
