<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create 
                            {--name=Admin : Nom de l\'administrateur}
                            {--email=admin@pickup.com : Email de l\'administrateur}
                            {--password=password : Mot de passe}';

    protected $description = 'Crée un compte administrateur';

    public function handle()
    {
        $name = $this->option('name');
        $email = $this->option('email');
        $password = $this->option('password');

        // Vérifier si l'utilisateur existe déjà
        if (User::where('email', $email)->exists()) {
            $this->error("❌ Un utilisateur avec l'email {$email} existe déjà.");
            return 1;
        }

        // Créer l'admin
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'phone' => '+000000000',
            'country' => 'Sénégal',
            'region' => 'Dakar',
            'city' => 'Dakar',
            'quarter' => 'Admin',
            'address' => 'Admin Address',
        ]);

        $this->info("✅ Compte administrateur créé avec succès !");
        $this->line("📧 Email : <info>{$email}</info>");
        $this->line("🔐 Mot de passe : <info>{$password}</info>");
        $this->line("🌐 Connectez-vous sur : <info>" . url('/login') . "</info>");

        return 0;
    }
}
