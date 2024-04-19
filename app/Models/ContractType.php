<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_contract_types', 'contract_type_id', 'employee_id');
    }
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_contract_types', 'contract_type_id', 'job_id');
    }
}
