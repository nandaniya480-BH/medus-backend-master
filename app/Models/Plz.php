<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plz extends Model
{
    use HasFactory;
    protected $fillable = [
        'plz','ort','latitude','longitude','berzirk','kantone_id'
    ];
    public function kantone()
    {
        return $this->belongsTo(Kantone::class, 'kantone_id');
    }

    public function employers()
    {
        return $this->hasMany(Employer::class, 'plz_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'plz_id');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'plz_id');
    }
}
