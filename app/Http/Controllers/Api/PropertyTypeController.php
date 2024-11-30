<?php

namespace App\Http\Controllers\Api;

use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyTypeResource;

class PropertyTypeController extends Controller
{
    public function index()
    {
        $propertyTypes = PropertyType::with('propertyFields')->get();
        return PropertyTypeResource::collection($propertyTypes);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:property_types|max:255',
            'fields' => 'required|array',
            'fields.*.field_name' => 'required|string|max:255',
            'fields.*.field_type' => 'required|string|in:text,number,boolean,date,select'
        ]);

        $propertyType = PropertyType::create([
            'name' => $validatedData['name']
        ]);

        foreach ($validatedData['fields'] as $field) {
            $propertyType->propertyFields()->create([
                'field_name' => $field['field_name'],
                'field_type' => $field['field_type']
            ]);
        }

        return new PropertyTypeResource($propertyType->load('propertyFields'));
    }

    public function show($id)
    {
        $propertyType = PropertyType::with('propertyFields')->findOrFail($id);
        return new PropertyTypeResource($propertyType);
    }

    public function update(Request $request, $id)
    {
        $propertyType = PropertyType::findOrFail($id);
        
        $validatedData = $request->validate([
            'name' => 'required|string|unique:property_types,name,' . $id . '|max:255',
            'fields' => 'sometimes|array',
            'fields.*.field_name' => 'required|string|max:255',
            'fields.*.field_type' => 'required|string|in:text,number,boolean,date,select'
        ]);

        $propertyType->update([
            'name' => $validatedData['name']
        ]);

        if (isset($validatedData['fields'])) {
            $propertyType->propertyFields()->delete();
            foreach ($validatedData['fields'] as $field) {
                $propertyType->propertyFields()->create([
                    'field_name' => $field['field_name'],
                    'field_type' => $field['field_type']
                ]);
            }
        }

        return new PropertyTypeResource($propertyType->load('propertyFields'));
    }
    public function getFormFields($id)
    {
        $propertyType = PropertyType::with('propertyFields')->findOrFail($id);
        return response()->json([
            'property_type' => $propertyType->name,
            'fields' => $propertyType->propertyFields->map(function($field) {
                return [
                    'name' => $field->field_name,
                    'type' => $field->field_type,
                    'component' => $this->getFormComponent($field->field_type)
                ];
            })
        ]);
    }

    private function getFormComponent($fieldType)
    {
        switch ($fieldType) {
            case 'number':
                return 'input-number';
            case 'boolean':
                return 'checkbox';
            case 'date':
                return 'date-picker';
            case 'select':
                return 'select';
            default:
                return 'input-text';
        }
    }
    public function destroy($id)
    {
        $propertyType = PropertyType::findOrFail($id);
        $propertyType->delete();
        return response()->json(null, 204);
    }
}