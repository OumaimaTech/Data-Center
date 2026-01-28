<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'type' => $this->faker->randomElement(['info', 'success', 'warning', 'error']),
            'read_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => now(),
        ]);
    }

    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => null,
        ]);
    }

    public function info(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'info',
        ]);
    }

    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'success',
        ]);
    }

    public function warning(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'warning',
        ]);
    }

    public function error(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'error',
        ]);
    }
}
