<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogData extends Model
{

    protected $table = 'logs_data';
    protected $fillable = [
        'email',
        'ip_address',
        'device',
        'location',
        'reason',
        'attempt_count',
        'is_blocked'
    ];
}
