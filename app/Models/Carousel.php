<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carousel extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'image', 'body', 'button_name', 'button_link', 'video_text', 'video_id', 'project_id', 'buttons'];

    protected $casts = [
        'buttons' => 'array',
    ];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function video() {
        return $this->belongsTo(Video::class);
    }
}
