<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClaimFactory extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => Item::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(15),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
