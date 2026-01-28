<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        $startDate = Carbon::now()->addDays($this->faker->numberBetween(3, 30));
        $endDate = (clone $startDate)->addDays($this->faker->numberBetween(1, 45));

        return [
            'user_id' => User::factory(),
            'resource_id' => Resource::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'justification' => $this->faker->paragraph(),
            'approval_notes' => null,
            'approved_by' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'approved_by' => null,
            'approval_notes' => null,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'approved_by' => User::factory(),
            'approval_notes' => $this->faker->sentence(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'rejected',
            'approved_by' => User::factory(),
            'approval_notes' => $this->faker->sentence(),
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'start_date' => Carbon::now()->subDays(1),
            'end_date' => Carbon::now()->addDays(5),
            'approved_by' => User::factory(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->subDays(2),
            'approved_by' => User::factory(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
        ]);
    }

    public function future(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => Carbon::now()->addDays(10),
            'end_date' => Carbon::now()->addDays(15),
        ]);
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => Carbon::now()->subDays(20),
            'end_date' => Carbon::now()->subDays(10),
            'status' => 'completed',
        ]);
    }
}
