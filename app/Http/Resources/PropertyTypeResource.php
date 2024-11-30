<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'fields' => PropertyFieldResource::collection($this->whenLoaded('propertyFields')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}