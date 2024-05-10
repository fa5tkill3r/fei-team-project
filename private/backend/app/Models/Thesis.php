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
 * @property string $assignment_sk
 * @property string $abstract
 * @property string $abstract_sk
 * @property string $path
 * @property string $leader
 * @property string $students_name
 * @property string $tutors
 * @property string $solution
 *
 */
class Thesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'assignment',
        'assignment_sk',
        'abstract',
        'abstract_sk',
        'path',
        'leader',
        'students_name',
        'tutors',
        'solution'
    ];

}
