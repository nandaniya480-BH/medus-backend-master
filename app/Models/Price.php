<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','price'
    ];
    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_costs', 'price_id', 'job_id');
    }
}
