<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentCategory extends Model
{
    use HasFactory;

    public $table = 'incident_categories';

    public $fillable = [
        'name',
      ];
}