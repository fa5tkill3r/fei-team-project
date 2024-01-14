<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'completed' => $this->completed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            "users" => UserResource::collection($this->users),
            'tags' => TagResource::collection($this->tags),
            'responses' => TaskResponseResource::collection($this->responses),
        ];
    }
}
