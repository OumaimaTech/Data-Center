<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Serveurs',
                'Stockage',
                'Réseau',
                'Calcul haute performance',
                'Virtualisation',
                'Sécurité'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }

    public function servers(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Serveurs',
            'description' => 'Serveurs physiques et virtuels',
        ]);
    }

    public function storage(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Stockage',
            'description' => 'Solutions de stockage de données',
        ]);
    }

    public function network(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Réseau',
            'description' => 'Équipements réseau et connectivité',
        ]);
    }
}
