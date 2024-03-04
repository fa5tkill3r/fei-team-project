<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Task;
use App\Models\Incident;
Use App\Models\Team;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index(): AnonymousResourceCollection
    {
        $team = request()->get('team');
        if ($team) {
            return $this->getByTeam($team);
        }
        $tasks = Task::with(['users', 'tags', 'responses'])->get();

        return TaskResource::collection($tasks);
    }

    private function getByTeam($teamId): AnonymousResourceCollection
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $tasks = $team->tasks()->with(['users', 'tags', 'responses'])->get();
        return TaskResource::collection($tasks);
    }

    public function store(Request $request, $teamId): TaskResource
    {
        $team = $this->user->teams()->findOrFail($teamId);
        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_create == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->create([
            ...$request->all(),
            'created_by_id' => $this->user->id,
            'description' => $request->description ?? '',
        ]);

        if ($request->incident_id != null) { 
            $incident = Incident::findOrFail($request->incident_id);
            $task->incident()->associate($incident);
        }

        $users = $request->users ? $request->users : [];

        $team = request('team_id') ? Team::find(request('team_id')) : null;
        if ($team){
            $users[] = $team->users()->pluck('id');
        }

        foreach ($users as $user) {
            $task->users()->attach($user);
        }

        $tags = $request->tags ? $request->tags : [];

        foreach ($tags as $tag) {
            $task->tags()->attach($tag);
        }

        $task = $team->tasks()->with(['users', 'tags', 'responses', 'responses.user'])->findOrFail($task->id);

        return new TaskResource($task);
    }

    public function show($teamId, $taskId): TaskResource
    {
        $team = $this->user->teams()->findOrFail($teamId);
        $task = $team->tasks()
            ->with(['users', 'tags', 'responses', 'responses.user', 'parent', 'children'])
            ->findOrFail($taskId);

        return new TaskResource($task);
    }

    public function update(Request $request, $teamId, $taskId): TaskResource
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_create == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);

        if ($task->incident != null) {
            $task->incident->update($request->incident);
        }

        $task->parent_id = $request->parent;
        $task->update($request->except(['users', 'tags', 'parent']));

        $users = $request->users ? $request->users : [];
        $task->users()->sync($users);

        $tags = $request->tags ? $request->tags : [];
        $task->tags()->sync($tags);

        $task = $team->tasks()->with(['users', 'tags', 'responses', 'responses.user', 'parent'])->findOrFail($task->id);

        return new TaskResource($task);
    }

    public function destroy($teamId, $taskId)
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_delete == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);

        $task->delete();

        return response()->json(null, 204);
    }

    public function assignUser(Request $request, $teamId, $taskId)
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $user = $team->users()->findOrFail($request->user_id);

        $task->users()->attach($user);

        return response()->json($task->users, 200);
    }

    public function unassignUser(Request $request, $teamId, $taskId)
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $user = $team->users()->findOrFail($request->user_id);

        $task->users()->detach($user);

        return response()->json($task->users, 200);
    }
}
