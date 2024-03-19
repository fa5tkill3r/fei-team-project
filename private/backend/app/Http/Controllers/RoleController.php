<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    public function store(Request $request)
    {
        if ($this->user->super_admin == 0) {
            return response()->json(['error' => 'Dont have permissions to create role'], 403);
        }
        
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

    public function update(Request $request, $id)
    {
        if ($this->user->super_admin == 0) {
            return response()->json(['error' => 'Dont have permissions to update role'], 403);
        }

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return response()->json([
            'role' => $role,
        ]);
    }
}
