<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'employeId',
        'image',
        'user_id',
        'about',
        'address',
        'gender',
        'date_of_birth',
        'country',
        'job_id',
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the job associated with the employee.
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function responsibles()
{
    return $this->hasMany(Responsible::class, 'employe_id');
}



}
