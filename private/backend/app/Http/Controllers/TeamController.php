<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private $user;
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index()
    {
        return $this->user->teams()->get();
    }

    public function store(Request $request)
    {
        $team = Team::create($request->all());
        $adminRole = Role::where('slug', 'admin')->first();
        $team->users()->attach($this->user, ['role_id' => $adminRole->id]);

        return response()->json($team, 201);
    }

    public function show($id)
    {
        return $this->user->teams()->findOrFail($id);
    }
    
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $updater = $team->users()->findOrFail($this->user->id);
        
        dd($updater->pivot->role->name);

        if(!in_array($updater->pivot->role, ['Leader', 'Administrative'])) {
            return response()->json(['error' => 'Dont have permissions to edit team'], 403);
        }
        
        $team->update($request->all());

        return response()->json($team, 200);
    }

    public function destroy($id)
    {
        Team::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function addUser($id, Request $request){
        $team = Team::findOrFail($id);
        $user = User::findOrFail(request()->get('user_id'));

        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->user_add == 0) {
            return response()->json(['error' => 'Dont have permissions to add user'], 403);
        }

        $team->users()->attach($this->user, ['role_id' => $request->get('role_id')]);

        return response()->json($team->users()->get(), 200);
    }

    public function removeUser($id, Request $request){
        $team = Team::findOrFail($id);
        $user = User::findOrFail(request()->get('user_id'));
        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if($role->user_add == 0) {
            return response()->json(['error' => 'Dont have permissions to add user'], 403);
        }

        $team->users()->attach($this->user, ['role_id' => $request->get('role_id')]);

        $team->users()->detach($user);

        return response()->json($team->users()->get(), 200);
    }
}
