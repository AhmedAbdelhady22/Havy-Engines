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
        // Create admin user (or update if email already exists)
        User::updateOrCreate(
            ['email' => 'admin@havy.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        // Create a test customer user
        User::updateOrCreate(
            ['email' => 'customer@havy.com'],
            [
                'name' => 'Test Customer',
                'password' => Hash::make('customer123'),
                'is_admin' => false,
            ]
        );
    }
}
