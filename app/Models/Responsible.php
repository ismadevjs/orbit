<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsible extends Model
{
    use HasFactory;

    protected $table = 'responsibles';

    protected $fillable = ['employe_id', 'investor_id'];

    public function employe() {
        return $this->belongsTo(Employe::class, 'employe_id', 'id');
    }
    public function investor() {
        return $this->belongsTo(User::class, 'investor_id', 'id');
    }
    public function investors()
    {
        return $this->belongsToMany(Investor::class, 'responsible_investor', 'responsible_id', 'investor_id');
    }

}
