<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['maintenance', 'logo', 'logo_white', 'favicon', 'site_name', 'site_keywords', 'site_description', 'site_email',
        'site_phone', 'site_address', 'footer', 'maintenance', 'whatsapp', 'whatsapp_message', 'footer_big'];
}
