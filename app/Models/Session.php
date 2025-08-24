<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'sessions'; // Explicitly specify the table name
    public $timestamps = false;    // Sessions table typically doesn’t use timestamps
    protected $fillable = ['user_id', 'id', 'ip_address', 'user_agent', 'payload', 'last_activity'];
}