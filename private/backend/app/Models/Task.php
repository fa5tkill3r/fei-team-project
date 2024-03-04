<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Task Model
 *
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $deadline
 * @property string $description
 * @property string $severity
 * @property boolean $is_closed
 * @property string $resolution
 * @property int $created_by_id
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'deadline',
        'description',
        'severity',
        'is_closed',
        'resolution',
        'created_by_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'task_tag', 'task_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function responses()
    {
        return $this->hasMany(TaskResponse::class);
    }

    public function getStatus()
    {
        if ($this->is_closed) {
            return 'closed';
        }

        if($this->users()->count() === 0) {
            return 'new';
        }

        return 'in_progress';
    }

    public function getCommentsCount()
    {
        return $this->comments()->count();
    }
}
