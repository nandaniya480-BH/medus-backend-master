<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    protected $table = "employee_educations";
    protected $fillable = ["id","employee_id", "education_id", "start_date", "end_date"];
    use HasFactory;
    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }
}
