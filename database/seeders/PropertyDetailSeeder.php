<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyDetail;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyDetailSeeder extends Seeder
{
    public function run()
    {
        $properties = Property::with('propertyType.propertyFields')->get();

        foreach ($properties as $property) {
            $fieldValues = $this->generateFieldValues($property->propertyType);
            
            PropertyDetail::create([
                'property_id' => $property->id,
                'field_values' => $fieldValues
            ]);
        }
    }

    private function generateFieldValues($propertyType)
    {
        $fieldValues = [];

        foreach ($propertyType->propertyFields as $field) {
            $fieldValues[$field->field_name] = $this->generateValueByType(
                $field->field_type,
                $field->field_name
            );
        }

        return $fieldValues;
    }

    private function generateValueByType($type, $fieldName)
    {
        switch ($type) {
            case 'number':
                return $this->generateNumberValue($fieldName);
            
            case 'boolean':
                return (bool)rand(0, 1);
            
            case 'date':
                return now()->addDays(rand(-30, 30))->format('Y-m-d');
            
            case 'select':
                return $this->generateSelectValue($fieldName);
            
            case 'text':
            default:
                return $this->generateTextValue($fieldName);
        }
    }

    private function generateNumberValue($fieldName)
    {
        switch ($fieldName) {
            case 'bedrooms':
                return rand(1, 6);
            
            case 'bathrooms':
                return rand(1, 4);
            
            case 'floor':
            case 'floor_number':
                return rand(1, 20);
            
            case 'parking_spaces':
                return rand(0, 4);
            
            case 'total_area':
                return rand(50, 500);
            
            case 'price':
                return rand(100000, 1000000);
            
            case 'year_built':
                return rand(1960, 2023);
            
            default:
                return rand(1, 100);
        }
    }

    private function generateSelectValue($fieldName)
    {
        $options = [
            'property_condition' => ['New', 'Excellent', 'Good', 'Fair', 'Needs Work'],
            'heating_type' => ['Gas', 'Electric', 'Oil', 'Solar', 'None'],
            'cooling_type' => ['Central', 'Window Units', 'Split System', 'None'],
            'parking_type' => ['Garage', 'Carport', 'Street', 'None'],
            'view_type' => ['City', 'Mountain', 'Ocean', 'Garden', 'None'],
            'zoning_type' => ['Residential', 'Commercial', 'Industrial', 'Mixed Use'],
        ];

        if (isset($options[$fieldName])) {
            return $options[$fieldName][array_rand($options[$fieldName])];
        }

        return 'Option ' . rand(1, 5);
    }

    private function generateTextValue($fieldName)
    {
        $texts = [
            'amenities' => ['Pool', 'Gym', 'Security', 'Elevator', 'Garden'],
            'construction_material' => ['Brick', 'Wood', 'Concrete', 'Steel Frame'],
            'style' => ['Modern', 'Classical', 'Contemporary', 'Victorian'],
            'description' => ['Well maintained', 'Newly renovated', 'Prime location', 'Great investment'],
        ];

        if (isset($texts[$fieldName])) {
            return $texts[$fieldName][array_rand($texts[$fieldName])];
        }

        return 'Sample text for ' . $fieldName;
    }
}