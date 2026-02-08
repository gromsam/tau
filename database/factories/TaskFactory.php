<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rand_user_id = rand(1,3);

        return [
            'title' => 'User ID:' . $rand_user_id . ' | ' . fake()->sentence(),
            'status' => fake()->randomElement(TaskStatus::cases()),
            'user_id' => $rand_user_id,
        ];
    }
}
