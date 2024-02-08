<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Team Model
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * 
 * @method HasMany tasks()
 * @method BelongsToMany users()
 */
class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_team', 'team_id', 'user_id')
            ->withPivot('role_id');
    }

}
