<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Kaleungitan',
            'email' => 'admin@kaleungitan.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Beberapa user biasa
        User::factory(10)->create();
    }
}
