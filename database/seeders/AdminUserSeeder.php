<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@forum.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'last_active' => now(),
            'email_verified_at' => now(),
        ]);

    }
}
