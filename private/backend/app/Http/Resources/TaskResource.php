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
            'name' => $this->name,
            'deadline' => $this->deadline,
            'severity' => $this->severity,
            'created_by' => UserResource::make($this->createdBy),
            'incident_id' => $this->incident_id,
            'description' => $this->description,
            'status' => $this->getStatus(),
            'resolution' => $this->resolution,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'comments_count' => $this->getCommentsCount(),
            'users' => UserResource::collection($this->whenLoaded('users')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'responses' => TaskResponseResource::collection($this->whenLoaded('responses')),
            'parent' => TaskResource::make($this->whenLoaded('parent')),
            'children' => TaskResource::collection($this->whenLoaded('children')),
        ];
    }
}
