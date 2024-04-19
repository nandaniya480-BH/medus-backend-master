<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployerCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function employers()
    {
        return $this->hasMany(Employee::class, 'category_id');
    }
}
