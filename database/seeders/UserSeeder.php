<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@test.com',
                'password' => bcrypt('admin'),
            ]);

        User::factory()
            ->create([
                'name' => 'User 1',
                'email' => 'user1@test.com',
                'password' => bcrypt('user1'),
            ]);

        User::factory()
            ->create([
                'name' => 'User 2',
                'email' => 'user2@test.com',
                'password' => bcrypt('user2'),
            ]);
    }
}
