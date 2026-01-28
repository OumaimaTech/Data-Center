<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'category_id' => Category::factory(),
            'description' => $this->faker->paragraph(),
            'specifications' => [
                'cpu' => $this->faker->randomElement(['Intel Xeon', 'AMD EPYC', 'ARM']),
                'ram' => $this->faker->randomElement(['16GB', '32GB', '64GB', '128GB']),
                'storage' => $this->faker->randomElement(['500GB SSD', '1TB SSD', '2TB HDD']),
            ],
            'location' => $this->faker->randomElement(['Salle A', 'Salle B', 'Rack 1', 'Rack 2']),
            'status' => $this->faker->randomElement(['disponible', 'en_maintenance', 'indisponible']),
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disponible',
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'en_maintenance',
        ]);
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'indisponible',
        ]);
    }
    
    public function reserved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'reserve',
        ]);
    }
}
