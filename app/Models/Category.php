<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type_id'];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function faq()
    {
        return $this->hasMany(Faq::class);
    }


}
