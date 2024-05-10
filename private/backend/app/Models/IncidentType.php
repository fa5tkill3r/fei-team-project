<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * IncidentType Model
 *
 * @property int $id
 * @property string $name
 *
 */

class IncidentType extends Model
{
    use HasFactory;

    public $table = 'incident_types';

    public $fillable = [
        'name',
    ];
}
