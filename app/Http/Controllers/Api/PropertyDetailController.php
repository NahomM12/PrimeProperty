<?php

namespace App\Http\Controllers\Api;

use App\Models\Property;
use App\Models\PropertyDetail;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyDetailResource;

class PropertyDetailController extends Controller
{
    public function store(Request $request)
    {
        // Get property type fields
        $property = Property::findOrFail($request->property_id);
        $propertyType = PropertyType::with('propertyFields')->findOrFail($property->property_type_id);
        
        // Build validation rules dynamically
        $validationRules = ['property_id' => 'required|exists:properties,id'];
        foreach ($propertyType->propertyFields as $field) {
            $rule = $this->getValidationRule($field->field_type);
            $validationRules["field_values.{$field->field_name}"] = $rule;
        }

        $validatedData = $request->validate($validationRules);

        $propertyDetail = PropertyDetail::create([
            'property_id' => $validatedData['property_id'],
            'field_values' => $request->field_values
        ]);

        return new PropertyDetailResource($propertyDetail);
    }

    public function update(Request $request, $id)
    {
        $propertyDetail = PropertyDetail::findOrFail($id);
        $property = Property::findOrFail($propertyDetail->property_id);
        $propertyType = PropertyType::with('propertyFields')->findOrFail($property->property_type_id);

        // Build validation rules dynamically
        $validationRules = [];
        foreach ($propertyType->propertyFields as $field) {
            $rule = $this->getValidationRule($field->field_type);
            $validationRules["field_values.{$field->field_name}"] = $rule;
        }

        $validatedData = $request->validate($validationRules);

        $propertyDetail->update([
            'field_values' => $request->field_values
        ]);

        return new PropertyDetailResource($propertyDetail);
    }

    private function getValidationRule($fieldType)
    {
        switch ($fieldType) {
            case 'number':
                return 'numeric';
            case 'boolean':
                return 'boolean';
            case 'date':
                return 'date';
            case 'select':
                return 'string';
            default:
                return 'string';
        }
    }
}