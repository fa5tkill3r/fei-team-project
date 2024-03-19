<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Role Model
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property boolean $task_access
 * @property boolean $task_create
 * @property boolean $task_delete
 * @property boolean $user_access
 * @property boolean $user_add
 * @property boolean $user_remove
 * @property boolean $team_info
 *
 * @method BelongsToMany users()
 * @method BelongsToMany teams()
 */
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'task_access',
        'task_create',
        'task_delete',
        'user_access',
        'user_add',
        'user_remove',
        'team_info',
    ];
   protected $casts = [
        'task_access' => 'boolean',
        'task_create' => 'boolean',
        'task_delete' => 'boolean',
        'user_access' => 'boolean',
        'user_add' => 'boolean',
        'user_remove' => 'boolean',
        'role_access' => 'boolean',
        'role_add' => 'boolean',
        'role_delete' => 'boolean',
        'team_info' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_team', 'user_id', 'team_id', 'role_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_team', 'team_id', 'user_id', 'role_id');
    }
}
