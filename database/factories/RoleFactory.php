<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Invité',
                'Utilisateur interne',
                'Responsable technique',
                'Administrateur'
            ]),
            'description' => $this->faker->sentence(),
        ];
    }

    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Invité',
            'description' => 'Peut consulter les ressources disponibles en mode lecture seule',
        ]);
    }

    public function internalUser(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Utilisateur interne',
            'description' => 'Ingénieur / Enseignant / Doctorant - Peut faire des demandes de réservation',
        ]);
    }

    public function technicalManager(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Responsable technique',
            'description' => 'Gestionnaire chargé d\'un ensemble de ressources du Data Center',
        ]);
    }

    public function administrator(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Administrateur',
            'description' => 'Gestion complète des utilisateurs, rôles et ressources',
        ]);
    }
}
