<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftSkill extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'approved', 'index'
    ];
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_soft_skills', 'soft_skill_id', 'employee_id');
    }
    public function jobs()
    {
        return $this->belongsToMany(Employee::class, 'job_soft_skills', 'soft_skill_id', 'job_id');
    }
}
