<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:active,inactive,available,unavailable',
            'propertyUse' => 'required|string|in:rent,sale',
            'property_type_id' => 'required|exists:property_types,id',
            'field_values' => 'required|array',
            'latitude' => 'required|numeric', 
            'longitude' => 'required|numeric',
        ];
    }
}