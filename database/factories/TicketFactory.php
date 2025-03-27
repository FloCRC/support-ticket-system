<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'priority' => rand(1,3),
            'status' => rand(1,3),
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'label_id' => Label::factory(),
        ];
    }
}
