<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@pickup.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'phone' => '0000000000',
                'role' => 'admin',
                'solde' => 0,
                'email_verified_at' => now(),
            ]
        );
    }
}
