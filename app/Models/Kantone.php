<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kantone extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'short_name'
    ];
    public function plzs()
    {
        return $this->hasMany(Plz::class, 'kantone_id');
    }

    public function employers()
    {
        return $this->hasMany(Employer::class, 'kantone_id');
    }
    public function employees()
    {
        return $this->hasMany(Employee::class, 'kantone_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'kantone_id');
    }
}
