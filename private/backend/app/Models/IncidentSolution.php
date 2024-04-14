<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentSolution extends Model
{
    use HasFactory;

    public $table = 'incident_solutions';

    public $fillable = [
        'name',
        'description',
        'name_of_creator',
        'deadline'
    ];

}
