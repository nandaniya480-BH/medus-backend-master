<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $table = 'educations';
    protected $fillable = [
        'name'
    ];
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_educations', 'education_id', 'employee_id');
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_educations', 'education_id', 'job_id');
    }
}
