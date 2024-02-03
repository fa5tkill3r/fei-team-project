<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        "email",
        "password",
        "first_name",
        "last_name",
        "remember_token"
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
