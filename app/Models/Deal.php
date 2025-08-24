<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table = 'deals';

    protected $fillable = [
        'lead_id',
        'responsible_id',
        'status',
        'user_id'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function manager()
    {
        return $this->belongsTo(SignRole::class, 'manager_id', 'responsible_id');
    }
}
