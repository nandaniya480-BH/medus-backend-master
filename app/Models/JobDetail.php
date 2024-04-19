<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_date',
        'by_arrangement',
        'employer_description',
        'job_content',
        'job_description',
        'c_person_name',
        'c_person_last_name',
        'c_person_email',
        'c_person_phone',
        'c_person_fax',
        'job_file_url',
        'job_url',
        'apply_form_url',
        'job_id',
    ];
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }
}
