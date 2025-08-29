<?php

// app/Models/Translation.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model {
    protected $fillable = ['key','language_id','value'];
    public function language() {
        return $this->belongsTo(Language::class);
    }
}
