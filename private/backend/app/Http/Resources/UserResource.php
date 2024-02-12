<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            "email" => $this->email,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "avatar" => $this->avatar ? asset('storage/' . $this->avatar) : null,
            // "tasks" => TaskResource::collection($this->tasks),
            // "task_responses" => TaskResponseResource::collection($this->task_responses),
        ];
    }
}
