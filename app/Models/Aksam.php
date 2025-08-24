<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aksam extends Model
{
    use HasFactory;

    public $fillable = ['name', 'description', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
