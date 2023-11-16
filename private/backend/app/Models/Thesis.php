<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'assignment',
        'path',
        'leader',
        'students_name',
    ];
}
