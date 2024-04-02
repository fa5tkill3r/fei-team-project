<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\TeamResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeamController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index(): AnonymousResourceCollection
    {
        $teams = $this->user->teams()->with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])->get();
        return TeamResource::collection($teams);
    }

    public function store(Request $request): TeamResource
    {
        $team = Team::create($request->all());

        if ($team) {
            $adminRole = Role::where('slug', 'admin')->first();
            $team->users()->attach($this->user->id, ['role_id' => $adminRole->id]);

            $team = Team::with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])
                ->findOrFail($team->id);

            return TeamResource::make($team);

        } else {
            return response()->json(['error' => 'Error creating team'], 500);
        }
    }

    public function show($id): TeamResource
    {
        $team = $this->user->teams()->findOrFail($id)->with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])->get();
        return TeamResource::make($team);
    }

    public function update(Request $request, $id): TeamResource
    {
        $team = Team::findOrFail($id);
        $updater = $team->users()->findOrFail($this->user->id);

        $role = $team->users()->findOrFail($this->user->id)->pivot->role;

        if ($role->team_info == 0) {
            return response()->json(['error' => 'Dont have permissions to add user'], 403);
        }

        $team->update($request->all());

        $team = Team::with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])
            ->findOrFail($team->id);

        return TeamResource::make($team);
    }

    public function destroy($id)
    {
        Team::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function addUser($id, Request $request): TeamResource
    {
        $team = Team::findOrFail($id);
        $users = User::whereIn('id', $request->get('users'))->get();

        $role = Role::find($team->users()->findOrFail($this->user->id)->pivot->role_id);

        if ($role->user_add == 0) {
            return response()->json(['error' => 'Dont have permissions to add user'], 403);
        }


        if ($users->count() == 0) {
            return response()->json(['error' => 'No users to add'], 400);
        }

        $memberRole = Role::where('slug', 'member')->first();
        $team->users()->attach($users, ['role_id' => $memberRole->id]);

        $team = Team::with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])
            ->findOrFail($team->id);

        return TeamResource::make($team);
    }

    public function removeUser($id, Request $request): TeamResource
    {
        $team = Team::findOrFail($id);
        $user = User::findOrFail(request()->get('user_id'));

        $role = Role::find($team->users()->findOrFail($this->user->id)->pivot->role_id);

        if ($role->user_remove == 0) {
            return response()->json(['error' => 'Dont have permissions to remove user'], 403);
        }

        $team->users()->detach($user);

        $team = Team::with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])
            ->findOrFail($team->id);

        return TeamResource::make($team);
    }

    public function updateUserRole($id, Request $request): TeamResource
    {
        $team = Team::findOrFail($id);
        $user = User::findOrFail(request()->get('user_id'));

        $updater = $team->users()->findOrFail($this->user->id);


        if ($updater->super_admin == 0) {
            return response()->json(['error' => 'Dont have permissions to update user'], 403);
        }

        $role = Role::findOrFail(request()->get('role_id'));

        $team->users()->updateExistingPivot($user->id, ['role_id' => $role->id]);

        $team = Team::with(['users', 'tasks', 'tasks.users', 'tasks.tags', 'tasks.responses', 'tasks.responses.user'])
            ->findOrFail($team->id);

        return TeamResource::make($team);
    }
}
