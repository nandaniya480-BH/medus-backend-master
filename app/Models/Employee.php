<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'last_name',
        'gender',
        'age',
        'phone',
        'mobile',
        'address',
        'position',
        'work_time',
        'description',
        'prefered_distance',
        'leadership',
        'workload_from',
        "workload_to",
        'ort',
        'kantone_id',
        'plz_id'
    ];
    // protected function workload(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => json_decode($value, true),
    //         set: fn ($value) => json_encode($value),
    //     );
    // }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function kantone()
    {
        return $this->belongsTo(Kantone::class, 'kantone_id')
            ->select(['id', 'name', 'short_name']);
    }
    public function plz()
    {
        return $this->belongsTo(Plz::class, 'plz_id')
            ->select(['id', 'plz', 'ort', 'berzirk', 'kantone_id']);
    }

    public function work_experiences()
    {
        return $this->hasMany(EmployeeExperience::class, 'employee_id')->orderByDesc('start_date');
    }
    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'employee_id');
    }
    public function contract_types()
    {
        return $this->belongsToMany(ContractType::class, 'employee_contract_types', 'employee_id', 'contract_type_id')
            ->select(['contract_types.id', 'contract_types.name'])->withTimestamps();
    }

    public function job_sub_categories()
    {
        return $this->belongsToMany(JobSubCategory::class, 'employee_categories', 'employee_id', 'job_subcategory_id')
            ->select(['job_sub_categories.id', 'job_sub_categories.name'])->withTimestamps();
    }

    public function soft_skills()
    {
        return $this->belongsToMany(SoftSkill::class, 'employee_soft_skills', 'employee_id', 'soft_skill_id')
            ->select(['soft_skills.id', 'soft_skills.name'])->withTimestamps();
    }
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'employee_languages', 'employee_id', 'language_id')

            ->select(['languages.id', 'languages.name', 'employee_languages.level'])->withTimestamps();
    }
    public function educations()
    {
        return $this->belongsToMany(Education::class, 'employee_educations', 'employee_id', 'education_id')
            ->select([
                'employee_educations.start_date',
                'employee_educations.end_date',
                'employee_educations.id',
                'employee_educations.education_id',
                'educations.name',

            ])->orderByDesc('employee_educations.start_date')->withTimestamps();
    }

    public function favourites()
    {
        return $this->belongsToMany(Job::class, 'job_favourites', 'employee_id', 'job_id')->withTimestamps();
    }

    public function contacted_jobs()
    {
        return $this->belongsToMany(Job::class, 'contacted_employees', 'employee_id', 'job_id')
        ->withPivot('employee_response', 'id')
        ->withTimestamps();
    }

    public function contacted_employer()
    {
        return $this->belongsToMany(Employer::class, 'contacted_employees', 'employee_id', 'employer_id')
            ->withPivot('employee_response')->withTimestamps();
    }

    public function suggestetd_jobs()
    {
        return $this->belongsToMany(Job::class, 'suggested_employees', 'employee_id', 'job_id')
            ->withPivot('employee_response', 'employer_response', 'employee_match', 're_suggest')
            ->withTimestamps();
    }

    public function experiences()
    {
        return $this->hasMany(EmployeeExperience::class, 'employee_id');
    }
}
