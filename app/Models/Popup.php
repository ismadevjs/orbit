<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    use HasFactory;

    protected $table = 'popups';

    protected $fillable = [
        'title',
        'description',
        'image',
        'status',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];
}
