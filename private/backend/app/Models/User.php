<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * User Model
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property string $remember_token
 * @property string $remember_token_expires_at
 * @property boolean $super_admin
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        "email",
        "password",
        "first_name",
        "avatar",
        "last_name",
        "remember_token",
        'titles_before',
        'titles_after',
    ];

    protected $hidden = [
        "password",
        "remember_token",
        "remember_token_expires_at"
    ];

    protected $rules = [
        "email" => "required|email",
        "password" => "required",
        "first_name" => "required",
        "last_name" => "required",
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_team', 'user_id', 'team_id')->withPivot('role_id');
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
