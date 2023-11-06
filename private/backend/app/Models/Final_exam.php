<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Final_exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name',
        'description',
        'date',
        'assignment',
        'exam_path',
        'leader',
        'students_name',
    ];
}
