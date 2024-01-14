<?php

namespace App\Http\Controllers;

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
        return $task->responses;
    }

    public function store(Request $request, $taskId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->create($request->all());
        $response->user()->associate($this->user);
        $response->save();

        return response()->json([
            'data' => $response,
        ]);
    }

    public function show($taskId, $responseId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        return $task->responses()->findOrFail($responseId);
    }

    public function update(Request $request, $taskId, $responseId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);
        $response->update($request->all());

        return response()->json([
            'data' => $response,
        ]);
    }

    public function destroy($taskId, $responseId)
    {
        $task = $this->user->tasks()->findOrFail($taskId);
        $response = $task->responses()->findOrFail($responseId);
        $response->delete();

        return response()->json([
            'data' => $response,
        ]);
    }
}
