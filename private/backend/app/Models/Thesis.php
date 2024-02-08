<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Thesis Model
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $date
 * @property string $assignment
 * @property string $path
 * @property string $leader
 * @property string $students_name
 */
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
