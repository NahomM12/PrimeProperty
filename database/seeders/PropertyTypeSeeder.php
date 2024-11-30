<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use App\Models\PropertyField;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    public function run()
    {
        $propertyTypes = [
            [
                'name' => 'Apartment',
                'fields' => [
                    ['field_name' => 'bedrooms', 'field_type' => 'number'],
                    ['field_name' => 'bathrooms', 'field_type' => 'number'],
                    ['field_name' => 'floor', 'field_type' => 'number'],
                    ['field_name' => 'has_parking', 'field_type' => 'boolean'],
                    ['field_name' => 'furnished', 'field_type' => 'boolean']
                ]
            ],
            [
                'name' => 'House',
                'fields' => [
                    ['field_name' => 'bedrooms', 'field_type' => 'number'],
                    ['field_name' => 'bathrooms', 'field_type' => 'number'],
                    ['field_name' => 'garage_size', 'field_type' => 'number'],
                    ['field_name' => 'has_garden', 'field_type' => 'boolean'],
                    ['field_name' => 'has_pool', 'field_type' => 'boolean']
                ]
            ],
            [
                'name' => 'Commercial',
                'fields' => [
                    ['field_name' => 'total_floors', 'field_type' => 'number'],
                    ['field_name' => 'office_count', 'field_type' => 'number'],
                    ['field_name' => 'parking_spaces', 'field_type' => 'number'],
                    ['field_name' => 'has_reception', 'field_type' => 'boolean'],
                    ['field_name' => 'available_from', 'field_type' => 'date']
                ]
            ],
            [
                'name' => 'Land',
                'fields' => [
                    ['field_name' => 'total_area', 'field_type' => 'number'],
                    ['field_name' => 'zoning_type', 'field_type' => 'select'],
                    ['field_name' => 'is_developed', 'field_type' => 'boolean'],
                    ['field_name' => 'has_utilities', 'field_type' => 'boolean']
                ]
            ]
        ];

        foreach ($propertyTypes as $propertyType) {
            $type = PropertyType::create([
                'name' => $propertyType['name']
            ]);

            foreach ($propertyType['fields'] as $field) {
                PropertyField::create([
                    'property_type_id' => $type->id,
                    'field_name' => $field['field_name'],
                    'field_type' => $field['field_type']
                ]);
            }
        }
    }
}