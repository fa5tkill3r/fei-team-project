<?php

namespace App\Http\Controllers;

use App\Models\IncidentType;
use Illuminate\Http\Request;

class IncidentTypeController extends Controller
{
    public function index()
    {
        return response()->json(['data' => IncidentType::all()], 200);
    }

    public function store(Request $request)
    {
        if(!$request->name) {
            return response()->json(['message' => 'Name is required'], 400);
        }

        $user = auth()->user();

        if(!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to create a type'], 403);
        }

        $type = new IncidentType();
        $type->name = $request->name;
        $type->save();

        return response()->json(['data' => $type], 201);
    }

    public function update(Request $request, $id)
    {
        $type = IncidentType::find($id);

        if(!$type) {
            return response()->json(['message' => 'Type not found'], 404);
        }

        $user = auth()->user();

        if(!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to update a type'], 403);
        }

        $type->name = $request->name;
        $type->save();

        return response()->json(['message' => 'Type updated successfully'], 200);
    }

    public function destroy($id)
    {
        $type = IncidentType::find($id);

        if(!$type) {
            return response()->json(['message' => 'Type not found'], 404);
        }

        $user = auth()->user();

        if(!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to delete a type'], 403);
        }

        $type->delete();

        return response()->json(['message' => 'Type deleted successfully'], 200);
    }
}
