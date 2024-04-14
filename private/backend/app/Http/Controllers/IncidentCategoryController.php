<?php

namespace App\Http\Controllers;

use App\Models\IncidentCategory;
use Illuminate\Http\Request;

class IncidentCategoryController extends Controller
{
    public function index()
    {
        return response()->json(['data' => IncidentCategory::all()], 200);
    }

    public function store(Request $request)
    {
        if (!$request->name) {
            return response()->json(['message' => 'Name is required'], 400);
        }

        $user = auth()->user();

        if (!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to create a category'], 403);
        }

        $category = new IncidentCategory();
        $category->name = $request->name;
        $category->save();

        return response()->json(['data' => $category], 201);
    }

    public function update(Request $request, $id)
    {
        $category = IncidentCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $user = auth()->user();

        if (!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to update a category'], 403);
        }

        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category updated successfully'], 200);
    }

    public function destroy($id)
    {
        $category = IncidentCategory::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $user = auth()->user();

        if (!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to delete a category'], 403);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
