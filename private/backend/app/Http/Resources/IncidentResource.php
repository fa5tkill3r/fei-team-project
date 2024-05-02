<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncidentResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'type' => $this->type,
            'phone_number' => $this->phone_number,
            'department' => $this->department,
            'ais_id' => $this->ais_id,
            'source' => $this->source,
            'images' => $this->images,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'task' => IncidentTaskResource::make($this->whenLoaded('task')),
//            'additional_info' => AdditionalIncidentInfoResource::make($this->whenLoaded('additionalInfo')),
        ];
    }
}
