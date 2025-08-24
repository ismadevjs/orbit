<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;


    protected $fillable = ['name', 'content', 'type_id'];

    public function type() {
        return $this->belongsTo(ContractType::class, 'type_id', 'id');
    }
}
