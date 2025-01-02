<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'address' => $this->faker->address,
            'price' => $this->faker->numberBetween(50000, 500000),
            'status' => $this->faker->randomElement(['available', 'sold', 'rented']),
            'owner' => $this->faker->name,
            'property_use' => $this->faker->randomElement(['sale', 'rent']),
            'property_type_id' => $this->faker->numberBetween(1, 10),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'images' => $this->faker->imageUrl(),
            'field_values' => $this->faker->words(5, true),
            'is_featured' => $this->faker->boolean,
            'region_id' => $this->faker->numberBetween(1, 5),
            'subregion_id' => $this->faker->numberBetween(1, 10),
            'location_id' => $this->faker->numberBetween(1, 20),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 3),
        ];
    }
}
