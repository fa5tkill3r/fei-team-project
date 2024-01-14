<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResponseResource;
use Illuminate\Http\Request;

class TaskResponseController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }


    public function index($taskId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        return TaskResponseResource::collection($task->responses);
    }

    public function store(Request $request, $taskId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->create($request->all());
        $response->user()->associate($this->user);
        $response->save();

        return TaskResponseResource::make($response);
    }

    public function show($taskId, $responseId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);

        return TaskResponseResource::make($response);
    }

    public function update(Request $request, $taskId, $responseId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);
        $response->update($request->all());

        return TaskResponseResource::make($response);
    }

    public function destroy($taskId, $responseId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);
        $response->delete();

        return TaskResponseResource::make($response);
    }
}
