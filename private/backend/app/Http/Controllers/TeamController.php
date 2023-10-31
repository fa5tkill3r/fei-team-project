<?php

namespace App\Http\Controllers;

use App\Models\Team;
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
        $team->users()->attach($this->user, ['role' => 'Leader']);

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
}
