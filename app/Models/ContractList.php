<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractList extends Model
{
    use HasFactory;

    protected $table = 'contract_list';

    protected $fillable = [
        'user_id',
        'user_role',
        'pdf_name',
        'pdf_path',
        'signature_user',
        'signature_pdf_company',
        'status',
    ];

    /**
     * Get the user that owns the contract.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
