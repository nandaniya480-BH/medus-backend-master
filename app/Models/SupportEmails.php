<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportEmails extends Model
{
    use HasFactory;
    protected $fillable = [
        'type', 'message', 'employer_id'
    ];
}
