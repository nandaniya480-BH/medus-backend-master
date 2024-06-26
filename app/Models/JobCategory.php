<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','category_id'
    ];
    public function subcategories()
    {
        return $this->hasMany(JobSubCategory::class,'category_id');
    }
    public function jobs()
    {
        return $this->hasMany(Job::class,'job_category_id');
    }
}
