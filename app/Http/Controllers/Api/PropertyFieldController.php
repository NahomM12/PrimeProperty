<?php

namespace App\Http\Controllers;

use App\Models\PropertyField;
use Illuminate\Http\Request;
use App\Http\Resources\PropertyFieldResource;

class PropertyFieldController extends Controller
{
    public function index()
    {
        $propertyFields = PropertyField::with('propertyType')->get();
        return PropertyFieldResource::collection($propertyFields);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'property_type_id' => 'required|exists:property_types,id',
            'field_name' => 'required|string|max:255',
            'field_type' => 'required|string|in:text,number,boolean,date,select'
        ]);

        $propertyField = PropertyField::create($validatedData);
        return new PropertyFieldResource($propertyField->load('propertyType'));
    }

    public function show($id)
    {
        $propertyField = PropertyField::with('propertyType')->findOrFail($id);
        return new PropertyFieldResource($propertyField);
    }

    public function update(Request $request, $id)
    {
        $propertyField = PropertyField::findOrFail($id);
        
        $validatedData = $request->validate([
            'property_type_id' => 'sometimes|exists:property_types,id',
            'field_name' => 'sometimes|string|max:255',
            'field_type' => 'sometimes|string|in:text,number,boolean,date,select'
        ]);

        $propertyField->update($validatedData);
        return new PropertyFieldResource($propertyField->load('propertyType'));
    }

    public function destroy($id)
    {
        $propertyField = PropertyField::findOrFail($id);
        $propertyField->delete();
        return response()->json(null, 204);
    }
}