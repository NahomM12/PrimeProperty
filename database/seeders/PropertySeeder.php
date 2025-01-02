<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Property;
use App\Models\Region;
use App\Models\SubRegion;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run()
    {
        // Create regions and subregions
        $region = Region::firstOrCreate(['region_name' => 'Central Region']);
        $subregion = SubRegion::firstOrCreate(['subregion_name' => 'Downtown', 'region_id' => $region->id]);

        // Create locations
        $location1 = Location::firstOrCreate(['location' => 'Downtown Area', 'subregion_id' => $subregion->id]);
        $location2 = Location::firstOrCreate(['location' => 'Studio Lane', 'subregion_id' => $subregion->id]);
        $location3 = Location::firstOrCreate(['location' => 'Family Road', 'subregion_id' => $subregion->id]);

        // Create properties
        Property::create([
            'title' => 'Luxury Downtown Apartment',
            'description' => 'Beautiful apartment in the heart of downtown',
            'address' => '789 Downtown Ave, City',
            'owner' => 1,
            'price' => 500000,
            'images' => json_encode(['images/Apartment1.jpg', 'images/Apartment2.jpg']), // JSON encode
            'status' => 'available',
            'property_use' => 'sale',
            'property_type_id' => 1, // Apartment
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'region_id' => $region->id,
            'subregion_id' => $subregion->id,
            'location_id' => $location1->id,
            'field_values' => json_encode([ // JSON encode
                'year_built' => '2020',
                'parking' => 'Yes',
                'air_conditioning' => 'Central',
                'floor_size' => '1200 sqft'
            ])
        ]);

        Property::create([
            'title' => 'Modern Studio',
            'description' => 'Cozy studio perfect for singles or couples',
            'address' => '101 Studio Lane, City',
            'owner' => 1,
            'price' => 1500,
            'images' => json_encode(['images/Studio1.jpg', 'images/Studio2.jpg']), // JSON encode
            'status' => 'available',
            'property_use' => 'rent',
            'property_type_id' => 4, // Studio
            'latitude' => 40.7142,
            'longitude' => -74.0064,
            'region_id' => $region->id,
            'subregion_id' => $subregion->id,
            'location_id' => $location2->id,
            'field_values' => json_encode([ // JSON encode
                'year_built' => '2019',
                'parking' => 'No',
                'air_conditioning' => 'Window Unit',
                'floor_size' => '500 sqft'
            ])
        ]);

        Property::create([
            'title' => 'Family House',
            'description' => 'Spacious family house with garden',
            'address' => '202 Family Road, Suburb',
            'owner' => 1,
            'price' => 750000,
            'images' => json_encode(['images/House1.jpg', 'images/House2.jpg', 'images/House3.jpg']), // JSON encode
            'status' => 'available',
            'property_use' => 'rent',
            'property_type_id' => 2, // House
            'latitude' => 40.7135,
            'longitude' => -74.0070,
            'region_id' => $region->id,
            'subregion_id' => $subregion->id,
            'location_id' => $location3->id,
            'field_values' => json_encode([ // JSON encode
                'year_built' => '2018',
                'parking' => 'Garage',
                'air_conditioning' => 'Central',
                'floor_size' => '2500 sqft',
                'garden_size' => '1000 sqft'
            ])
        ]);
    }
}
