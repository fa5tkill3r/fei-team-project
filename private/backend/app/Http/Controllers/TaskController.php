<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Incident;
use App\Models\Task;
use App\Models\TaskResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index() {
        $team = request()->get('team');
        if($team) {
            return $this->getByTeam($team);
        }
        $tasks = Task::all();
        return TaskResource::collection($tasks);
    }

    private function getByTeam($teamId) {
        $team = $this->user->teams()->findOrFail($teamId);
        return $team->tasks;
    }

    public function store(Request $request, $teamId) {
        $team = $this->user->teams()->findOrFail($teamId);
        
        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->task_create == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        

        $task = $team->tasks()->create($request->all());
        if($request->incident_id != null) {
            $incident = Incident::findOrFail($request->incident_id);    
            $task->incident()->associate($incident);
        }

        foreach($request->users as $user) {
            $task->users()->attach($user);
        }

        foreach($request->tags as $tag) {
            $task->tags()->attach($tag);
        }

        $task->users()->attach($this->user);

        $task = $team->tasks()->findOrFail($task->id);
        return new TaskResponse($task);
    }

    public function show($teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);
        $task = $team->tasks()->findOrFail($taskId);
        return new TaskResource($task);
    }

    public function update(Request $request, $teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);
        
        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        
        if($task->incident != null) {
            $task->incident->update($request->incident);
        }

        foreach($request->users as $user) {
            $task->users()->attach($user);
        }

        foreach($request->tags as $tag) {
            $task->tags()->attach($tag);
        }

        $response = new TaskResponse();
        $response->fill($request->task_response);
        $response->task()->associate($task);

        $task->update($request->all());

        return new TaskResource($task);
    }

    public function destroy($teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);

        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->task_delete == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $task->delete();

        return response()->json(null, 204);
    }

    public function assignUser(Request $request, $teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);

        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $user = $team->users()->findOrFail($request->user_id);

        $task->users()->attach($user);

        return response()->json($task->users, 200);
    }

    public function unassignUser(Request $request, $teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);

        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $user = $team->users()->findOrFail($request->user_id);

        $task->users()->detach($user);

        return response()->json($task->users, 200);
    }



}
