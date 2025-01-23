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
            'address' => 'sometimes|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|in:active,inactive,available,unavailable',
            'property_use' => 'required|string|in:rent,sale',
            'property_type_id' => 'required|exists:property_types,id',
            'field_values' => 'required|array',
            'latitude' => 'sometimes|numeric', 
            'longitude' => 'ssometimes|numeric',
        ];
    }
}