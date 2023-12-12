<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IncidentTask;

class IncidentTaskController extends Controller
{

    protected $user;
    public function __construct()
    {
        $this->user = auth()->user();
    }
    public function index()
    {
        $incidentTasks = IncidentTask::where('user_id', $this->user->id)->get();

        return response()->json([
            'data' => $incidentTasks,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = (new IncidentTask())->validate($data);
        $incidentTask = IncidentTask::create($request->all());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        return response()->json([
            'data' => $incidentTask,
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => IncidentTask::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $incidentTask = IncidentTask::findOrFail($id);

        if($request->has('type')){
            $incidentTask->incident()->type = $request->type;
            $incidentTask->incident()->save();
        }
        $incidentTask->update($request->all());

        return response()->json([
            'data' => $incidentTask,
        ]);
    }

    public function destroy($id)
    {
        $incidentTask = IncidentTask::findOrFail($id);
        $incidentTask->delete();

        return response()->json([
            'data' => $incidentTask,
        ]);
    }
    
}
