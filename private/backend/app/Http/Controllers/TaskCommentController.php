<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCommentResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskCommentController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index($taskId): AnonymousResourceCollection
    {
        $task = Task::findOrFail($taskId);
        $comments = $task->comments()->with('user')->paginate();

        return TaskCommentResource::collection($comments);
    }

    public function store(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $comment = $task->comments()->create([
            'user_id' => $this->user->id,
            'comment' => $request->comment,
            'show_in_report' => $request->show_in_report ?? false,
        ]);

        return TaskCommentResource::make($comment);
    }

    public function show($taskId, $commentId): TaskCommentResource
    {
        $task = Task::findOrFail($taskId);
        $comment = $task->comments()->with('user')->findOrFail($commentId);

        return TaskCommentResource::make($comment);
    }

    public function update(Request $request, $taskId, $commentId): TaskCommentResource
    {
        $task = Task::findOrFail($taskId);
        $comment = $task->comments()->findOrFail($commentId);
        if ($comment->user_id != $this->user->id) {
            return response()->json(['error' => 'Dont have permissions to update comment'], 403);
        }
        $comment->update($request->all());

        return TaskCommentResource::make($comment);
    }

    public function destroy($taskId, $commentId): TaskCommentResource
    {
        $task = Task::findOrFail($taskId);
        $comment = $task->comments()->findOrFail($commentId);
        if ($comment->user_id != $this->user->id) {
            return response()->json(['error' => 'Dont have permissions to delete comment'], 403);
        }
        $comment->delete();

        return TaskCommentResource::make($comment);
    }
}
