<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{

    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'link',
        'image'
    ];

    protected $table = 'socials';
}
