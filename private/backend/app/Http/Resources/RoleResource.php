<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'permissions' => [
                'task_access' => $this->task_access,
                'task_create' => $this->task_create,
                'task_delete' => $this->task_delete,
                'user_access' => $this->user_access,
                'user_add' => $this->user_add,
                'user_remove' => $this->user_remove,
                'role_access' => $this->role_access,
                'role_add' => $this->role_add,
                'role_delete' => $this->role_delete,
                'team_info' => $this->team_info,
            ],
        ];
    }
}
