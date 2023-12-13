<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'deadline',
        'description',
        'sevirity',
        'is_resolved',
        'is_closed',
        'resolution',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }


}
