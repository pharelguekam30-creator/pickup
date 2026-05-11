<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;

class DebugTestSeeder extends Seeder
{
    public function run(): void
    {
        // Création d'un vidangeur de test
        if (!User::where('email', 'vidangeur@test.com')->exists()) {
            User::create([
                'name' => 'Vidangeur Test',
                'email' => 'vidangeur@test.com',
                'password' => Hash::make('password'),
                'role' => 'vidangeur',
                'disponibilite' => 1,
            ]);
        }

        // Création d'un service de test
        if (!Service::where('name', 'Vidange simple')->exists()) {
            Service::create([
                'name' => 'Vidange simple',
                'description' => 'Service de vidange standard',
                'price' => 100.00,
            ]);
        }
    }
}
