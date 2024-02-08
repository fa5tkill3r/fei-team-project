<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\IncidentImage;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class IncidentsController extends Controller
{
    public function index() :AnonymousResourceCollection
    {
        return response()->json([
            'data' => Incident::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = (new Incident())->validate($data);
        $incident = Incident::create($request->all());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {
                $originalName = $image->getClientOriginalName();                
                $imagePath = $image->storeAs('images', $originalName, 'public');
                $imageModel = new IncidentImage();
                $imageModel->image = basename($imagePath);
                $imageModel->path = $imagePath;
                $imageModel->incident_id = $incident->id;
                $imageModel->save();
            }
        }

        return response()->json([
            'data' => $incident,
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Incident::find($id),
        ]);
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $incident->update($request->all());

        return response()->json([
            'data' => $incident,
        ]);
    }

    public function destroy($id)
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();

        return response()->json([
            'data' => $incident,
        ]);
    }

    public function getIncidentsByType($type)
    {
        return response()->json([
            'data' => Incident::where('type', $type)->get(),
        ]);
    }

    public function getImages($id)
    {
        return response()->json([
            'data' => Incident::find($id)->images,
        ]);
    }
}
