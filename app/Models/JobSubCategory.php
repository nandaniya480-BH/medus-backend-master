<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSubCategory extends Model
{
    use HasFactory;
    public function category()
    {
        return $this->belongsTo(JobCategory::class,'category_id');
    }
    public function jobs()
    {
        return $this->hasMany(Job::class,'job_subcategory_id');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_categories', 'job_subcategory_id', 'employee_id');
    }
}
