<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'address' => $this->address,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'price' => $this->price,
            'images' => collect($this->images)->map(function($image) {
                return url('storage/' . $image);
            }),
            'status' => $this->status,
            'propertyUse' => $this->propertyUse,
            'property_type_id' => $this->property_type_id,
            'property_type' => new PropertyTypeResource($this->whenLoaded('propertyType')),
            'field_values' => $this->field_values,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}