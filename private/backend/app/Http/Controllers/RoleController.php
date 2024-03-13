<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        $role = Role::create($request->all());

        return response()->json([
            'role' => $role,
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'role' => Role::find($id),
        ]);
    }

}
