<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentChronologically extends Model
{
    use HasFactory;

    public $table = 'incident_chronologically';

    public $fillable = [
        'description',
        'date',
        'additional_incident_info_id'
    ];
}
