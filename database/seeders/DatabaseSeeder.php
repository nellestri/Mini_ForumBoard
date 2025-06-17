<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminUser = User::where('email', 'admin@forum.com')->first();

        if (!$adminUser) {
            // Create admin user
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@forum.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'last_active' => now(),
                'email_verified_at' => now(),
            ]);

            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@forum.com');
            $this->command->info('Password: password');
        } else {
            // Update existing user to admin
            $adminUser->update([
                'role' => 'admin',
                'last_active' => now(),
            ]);

            $this->command->info('Existing user updated to admin role!');
        }

        // Create some test users
        $testUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        ];

        foreach ($testUsers as $userData) {
            if (!User::where('email', $userData['email'])->exists()) {
                User::create(array_merge($userData, [
                    'last_active' => now(),
                    'email_verified_at' => now(),
                ]));
            }
        }

        $this->command->info('Test users created successfully!');
    }
}
