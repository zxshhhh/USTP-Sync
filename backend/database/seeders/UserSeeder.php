<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'zhoulopagalan13@gmail.com',
            'password' => Hash::make('password'), // Your desired password, hashed!
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // You can also use the model factory if you have one
        // \App\Models\User::factory()->create([
        //     'email' => 'another@example.com',
        //     'password' => Hash::make('password123'),
        // ]);
    }
}