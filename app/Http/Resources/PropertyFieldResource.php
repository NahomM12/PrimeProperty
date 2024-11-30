<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyFieldResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'property_type_id' => $this->property_type_id,
            'field_name' => $this->field_name,
            'field_type' => $this->field_type,
            'property_type' => new PropertyTypeResource($this->whenLoaded('propertyType')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}