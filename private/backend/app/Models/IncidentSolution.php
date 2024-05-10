<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * IncidentSolution Model
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $name_of_responsible_person
 * @property string $deadline
 */
class IncidentSolution extends Model
{
    use HasFactory;

    public $table = 'incident_solutions';

    public $fillable = [
        'name',
        'description',
        'name_of_responsible_person',
        'deadline'
    ];

}
