<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResponseResource;
use App\Models\TaskResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskResponseController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }


    public function index($taskId): AnonymousResourceCollection
    {
        $tasks = $this->user->tasks()->findOrFail($taskId);

        $responses = $tasks->responses()->with(['user', 'task'])->get();     

        return TaskResponseResource::collection($responses);
    }

    public function store(Request $request, $taskId): TaskResponseResource
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $request->merge(['user_id' => $this->user->id]);
        $request->merge(['task_id' => $task->id]);
        $response = $task->responses()->create($request->all());
        
        
        $response->save();
        
        $response = TaskResponse::with(['user', 'task'])->findOrFail($response->id);

        return TaskResponseResource::make($response);
    }

    public function show($taskId, $responseId): TaskResponseResource
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);

        $response = $response->with(['user', 'task']);

        return TaskResponseResource::make($response);
    }

    public function update(Request $request, $taskId, $responseId): TaskResponseResource
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);
        $response->update($request->all());

        $response = $response->with(['user', 'task']);

        return TaskResponseResource::make($response);
    }

    public function destroy($taskId, $responseId): TaskResponseResource
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);
        $response->delete();
        
        $response->with(['user', 'task']);


        return TaskResponseResource::make($response);
    }
}
