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
            'images' => $this->images,
            'status' => $this->status,
            'property_use' => $this->property_use,
            'property_type_id' => $this->property_type_id,
            'property_type' => $this->whenLoaded('propertyType'),
            'region' => [
                'id' => $this->region->id,
                'name' => $this->region->region_name,
            ],
            'subregion' => [
                'id' => $this->subregion->id,
                'name' => $this->subregion->subregion_name,
            ],
            'location' => [
                'id' => $this->location->id,
                'name' => $this->location->location,
            ],
            'field_values' => $this->field_values,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_featured' => $this->is_featured,
            'views_count' => $this->views()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
