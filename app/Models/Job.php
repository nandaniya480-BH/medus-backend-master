<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_id',
        'job_title',
        'ort',
        'position',
        'work_experience',
        'work_time',
        'slug',
        'leadership',
        'is_active',
        'employer_id',
        'employer_category_id',
        'job_category_id',
        'job_subcategory_id',
        'contract_type_id',
        'workload_from',
        "workload_to",
        "on_top",
        "is_promoted",
        'plz_id',
        'kantone_id',
    ];
    // protected function workload(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => json_decode($value, true),
    //         set: fn ($value) => json_encode($value),
    //     );
    // }
    public function contract_type()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id');
    }
    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id');
    }

    public function employer_category()
    {
        return $this->belongsTo(EmployerCategory::class, 'employer_category_id');
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class, 'job_category_id');
    }
    public function job_subcategory()
    {
        return $this->belongsTo(JobSubCategory::class, 'job_subcategory_id');
    }
    public function kantone()
    {
        return $this->belongsTo(Kantone::class, 'kantone_id');
    }
    public function plz()
    {
        return $this->belongsTo(Plz::class, 'plz_id');
    }
    public function job_details()
    {
        return $this->hasOne(JobDetail::class, 'job_id');
    }

    public function soft_skills()
    {
        return $this->belongsToMany(SoftSkill::class, 'job_soft_skills', 'job_id', 'soft_skill_id')
            ->select(['soft_skills.id', 'soft_skills.name'])->withTimestamps();
    }
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'job_languages', 'job_id', 'language_id')
            ->select(['languages.id', 'languages.name', 'job_languages.level'])->withTimestamps();
    }

    public function educations()
    {
        return $this->belongsToMany(Education::class, 'job_educations', 'job_id', 'education_id')
            ->select(['educations.id', 'educations.name'])->withTimestamps();
    }

    public function prices()
    {
        return $this->belongsToMany(Price::class, 'job_costs', 'job_id', 'price_id')
            ->select(['price_id', 'prices.name', 'prices.price', 'job_costs.id', 'job_costs.is_paid', "job_costs.employer_email"])->withTimestamps();
    }

    public function favourites()
    {
        return $this->belongsToMany(Employee::class, 'job_favourites', 'job_id', 'employee_id')->withTimestamps();
    }

    public function contacted_employees()
    {
        return $this->belongsToMany(Employee::class, 'contacted_employees', 'job_id', 'employee_id')
            ->withPivot('employee_response');
    }

    public function suggestetd_employees()
    {
        return $this->belongsToMany(Employee::class, 'suggested_employees', 'job_id', 'employee_id')
            ->withPivot('employee_response', 'employer_response', 'employee_match', 're_suggest');
        ;
    }
}