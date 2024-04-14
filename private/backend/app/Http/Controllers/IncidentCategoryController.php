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
        if(!$request->name) {
            return response()->json(['message' => 'Name is required'], 400);
        }

        $user = auth()->user();

        if(!$user?->super_admin) {
            return response()->json(['message' => 'You do not have permission to create a category'], 403);
        }

        $category = new IncidentCategory();
        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category created successfully'], 201);
    }
}
