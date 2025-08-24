<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'area',
        'location',
        'images',
        'facilities',
        'distances',
        'custom_field_names',
        'custom_field_values',
        'video_links',
        'speciality_video',
        'detailed_video',
        'project_types',
        'surfaces',
        'prices',
        'user_id'
    ];

    protected $casts = [
        'location' => 'array',
        'images' => 'array',
        'facilities' => 'array',
        'distances' => 'array',
        'custom_field_names' => 'array',
        'custom_field_values' => 'array',
        'video_links' => 'array',
        'project_types' => 'array',
        'surfaces' => 'array',
        'prices' => 'array',

    ];
    protected $dates = ['created_at', 'updated_at'];

    public function area()
    {
        return $this->belongsTo(Area::class, 'area');
    }

    public function getFacilities()
    {
        return $this->belongsToMany(Facility::class, 'facilities');
    }

}
