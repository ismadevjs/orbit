<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignRole extends Model
{
    use HasFactory;

    protected $table = 'call_centers_roles';
    protected $fillable = ['manager_id', 'call_center_id'];

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function callCenter() {
        return $this->belongsTo(User::class, 'call_center_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

}
