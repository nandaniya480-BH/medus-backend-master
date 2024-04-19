<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    protected $table = "languages";
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_languages', 'language_id', 'employee_id');
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_languages', 'language_id', 'job_id');
    }
}
