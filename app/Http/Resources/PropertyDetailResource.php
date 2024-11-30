<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'property' => new PropertyResource($this->whenLoaded('property')),
            'fields' => $this->field_values,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}