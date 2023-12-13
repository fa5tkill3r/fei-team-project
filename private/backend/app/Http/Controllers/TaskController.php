<?php

namespace App\Http\Controllers;

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
        $teams = $this->user->teams()->get();

        $tasks = [];

        foreach ($teams as $team) {
            $tasks[] = [
                'team' => $team,
                'tasks' => $team->tasks,
            ];
        }




        // $teams = $this->user->tasks()->with('team')->get();
        // $tasks = [];

        // foreach ($teams as $team) {
        //     $tasks[] = [
        //         'team' => $team,
        //         'tasks' => $team->tasks,
        //     ];
        // }
        // return response()->json($tasks, 200);
    }



    public function getByTeam($teamId) {
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
        $task->users()->attach($this->user);

        return response()->json($task, 201);
    }

    public function show($teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);
        return $team->tasks()->findOrFail($taskId);
    }

    public function update(Request $request, $teamId, $taskId) {
        $team = $this->user->teams()->findOrFail($teamId);
        
        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $task->update($request->all());

        return response()->json($task, 200);
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
