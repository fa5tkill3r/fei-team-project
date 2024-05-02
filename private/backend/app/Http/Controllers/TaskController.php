<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Task;
use App\Models\Incident;
use App\Search\SearchLexer;
use App\Search\Token;
use App\Search\TokenType;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Models\AdditionalIncidentInfo;

function getFirstName(string $name): string
{
    $pos = strpos($name, '.');
    return $pos === false ? $name : substr($name, 0, $pos);
}

function getLastName(string $name): string|null
{
    $pos = strpos($name, '.');
    return $pos === false ? null : substr($name, $pos + 1);
}

function searchByName($q, Token $token, $user)
{
    $name = $token->value;

    if ($name === '@me') {
        return $q->where('task_user.user_id', $user->id);
    }

    if (strchr($name, '.')) {
        $firstName = getFirstName($token->value);
        $lastName = getLastName($token->value);

        return $q
            ->where('first_name', 'like', "%$firstName%")
            ->where('last_name', 'like', "%$lastName%");
    }

    return $q->where('first_name', 'like', "%$name%")
        ->orWhere('last_name', 'like', "%$name%");
}

class TaskController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->user = auth()->user();
    }

    public function index(): AnonymousResourceCollection
    {
        $team = request()->get('team');
        if ($team) {
            return $this->getByTeam($team);
        }

        $tasks = Task::with(['users', 'tags', 'responses'])->get();

        return TaskResource::collection($tasks);
    }

    private function searchTasks($team, $search)
    {
        $lexer = new SearchLexer($search);
        $tokens = $lexer->tokenize();
        $query = $team->tasks()->with(['users', 'tags', 'responses']);
        $str = '';

        foreach ($tokens as $token) {
            if ($token->type == TokenType::STRING) {
                $str .= $token->value . ' ';
                continue;
            }

            $query = match ($token->type) {
                TokenType::STATUS => $query->where('is_closed', match ($token->value) {
                    'closed' => true,
                    default => false,
                }),
                TokenType::TAG => $query->whereHas('tags', fn($q) => $q->where('name', $token->value)),
                TokenType::ASSIGNEE => $query->whereHas('users', fn($q) => searchByName($q, $token, $this->user)),
                default => $query,
            };
        }

        $str = trim($str);
        if ($str) {
            $query = $query->where('name', 'like', "%$str%")
                ->orWhere('description', 'like', "%$str%");
        }

        return $query;
    }

    private function getByTeam($teamId): AnonymousResourceCollection
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $search = request()->get('query');
        if ($search) {
            $query = $this->searchTasks($team, $search);
            // dd($query->toSql(), $query->getBindings());
            $filtered = $query->get();

            return TaskResource::collection($filtered);
        }

        $tasks = $team->tasks()->with(['users', 'tags', 'responses'])->get();
        return TaskResource::collection($tasks);
    }

    public function store(Request $request, $teamId): TaskResource
    {
        $team = $this->user->teams()->findOrFail($teamId);
        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_create == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->create([
            ...$request->all(),
            'created_by_id' => $this->user->id,
            'description' => $request->description ?? '',
        ]);

        if ($request->incident_id != null) {
            $incident = Incident::findOrFail($request->incident_id);
            $task->incident()->associate($incident);
            $additionalInfo = AdditionalIncidentInfo::find($incident->id);
            if ($additionalInfo == null) {
                AdditionalIncidentInfo::create([
                    'incident_id' => $incident->id,
                ]);
            }

            $additionalInfo->incident_taken_by = $this->user->id;
            $additionalInfo->save();
        }

        $users = $request->users ? $request->users : [];

        if (request('all_users')) {
            $users = $team->users()->get()->pluck('id');
        }

        foreach ($users as $user) {
            $task->users()->attach($user);
        }

        $tags = $request->tags ? $request->tags : [];

        foreach ($tags as $tag) {
            $task->tags()->attach($tag);
        }

        $task->load(['users', 'tags', 'responses', 'responses.user']);
        $task->save();

        return new TaskResource($task);
    }

    public function show($teamId, $taskId): TaskResource
    {
        $team = $this->user->teams()->findOrFail($teamId);
        $task = $team->tasks()
            ->with(['users', 'tags', 'responses', 'responses.user', 'parent', 'children', 'incident'])
            ->findOrFail($taskId);

        return new TaskResource($task);
    }

    public function update(Request $request, $teamId, $taskId): TaskResource
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_create == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);

//        TODO: update incident
//        if ($task->incident != null) {
//            $task->incident->update($request->incident);
//        }

        $task->parent_id = $request->parent;
        $task->update($request->except(['users', 'tags', 'parent']));

        $users = $request->users ? $request->users : [];
        if (request('all_users')) {
            $users = $team->users()->get()->pluck('id');
        }

        $task->users()->sync($users);

        $tags = $request->tags ? $request->tags : [];
        $task->tags()->sync($tags);

        $task = $team->tasks()
            ->with(['users', 'tags', 'responses', 'responses.user', 'parent', 'incident'])
            ->findOrFail($task->id);

        return new TaskResource($task);
    }

    public function destroy($teamId, $taskId)
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_delete == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);

        $task->delete();

        return response()->json(null, 204);
    }

    public function assignUser(Request $request, $teamId, $taskId)
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $user = $team->users()->findOrFail($request->user_id);

        $task->users()->attach($user);

        return response()->json($task->users, 200);
    }

    public function unassignUser(Request $request, $teamId, $taskId)
    {
        $team = $this->user->teams()->findOrFail($teamId);

        $roleId = $team->users()->findOrFail($this->user->id)->pivot->role_id;
        $role = Role::findOrFail($roleId);

        if ($role->task_access == 0) {
            return response()->json(['error' => 'Dont have permissions to create task'], 403);
        }

        $task = $team->tasks()->findOrFail($taskId);
        $user = $team->users()->findOrFail($request->user_id);

        $task->users()->detach($user);

        return response()->json($task->users, 200);
    }
}
