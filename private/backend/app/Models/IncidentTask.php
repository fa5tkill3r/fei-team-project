<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentTask extends Model
{
    use HasFactory;
    protected $table = 'task_incident';

    protected $fillable = [
        'incident_id',
        'user_id',
        'is_resolved',
        'is_closed',
        'resolution',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
