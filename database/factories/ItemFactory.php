<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $items = [
            ['name' => 'MacBook Pro 14"', 'category' => 'Elektronik'],
            ['name' => 'Dompet Kulit Hitam', 'category' => 'Dompet/Kartu'],
            ['name' => 'Kunci Motor', 'category' => 'Kunci'],
            ['name' => 'Kartu Mahasiswa', 'category' => 'Dokumen'],
            ['name' => 'Kacamata Hitam', 'category' => 'Aksesoris'],
            ['name' => 'Payung Lipat', 'category' => 'Lainnya'],
        ];

        $picked = $this->faker->randomElement($items);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'category_id' => Category::where('name', $picked['category'])->first()->id
                ?? Category::inRandomOrder()->first()->id,
            'type' => $this->faker->randomElement(['hilang', 'temuan']),
            'name' => $picked['name'],
            'description' => $this->faker->sentence(10),
            'location' => $this->faker->randomElement([
                'Gedung A', 'Gedung B', 'Perpustakaan', 'Kantin', 'Parkiran', 'Laboratorium',
            ]),
            'date' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'photo' => null,
            'status' => $this->faker->randomElement([
                'pending', 'verified', 'claimed', 'resolved', 'rejected',
            ]),
        ];
    }
}
