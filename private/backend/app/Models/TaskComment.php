<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * TaskComment Model
 * 
 * @property int $id
 * @property string $comment
 * @property int $user_id
 * @property int $task_id
 * 
 * @method BelongsTo task()
 * @method BelongsTo user()
 * 
 */
class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';

    protected $fillable = [
        'comment',
        'user_id',
        'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}