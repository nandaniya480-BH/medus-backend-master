<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'fax',
        'size',
        'c_p_name',
        'c_p_surname',
        'c_p_email',
        'c_p_gender',
        'c_p_phone',
        'c_p_fax',
        'team_email',
        'description',
        'page_url',
        'ort',
        'kantone_id',
        'plz_id',
        'category_id',
        'holidays',
        'maternity_benefits',
        'benefits',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function kantone()
    {
        return $this->belongsTo(Kantone::class, 'kantone_id');
    }
    public function plz()
    {
        return $this->belongsTo(Plz::class, 'plz_id');
    }
    public function category()
    {
        return $this->belongsTo(EmployerCategory::class, 'category_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'employer_id')->orderByDesc('created_at');;
    }

    public function contacted_employees()
    {
        return $this->belongsToMany(Employee::class, 'contacted_employees', 'employer_id', 'employee_id');
    }
}
