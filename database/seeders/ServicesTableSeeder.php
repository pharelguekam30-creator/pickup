<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Collecte de poubelles ménagères',
                'description' => 'Ramassage régulier des déchets domestiques.',
                'price' => 2000,
            ],
            [
                'name' => 'Collecte de recyclables',
                'description' => 'Ramassage du papier, plastique, verre et métal.',
                'price' => 2500,
            ],
            [
                'name' => 'Collecte de déchets organiques',
                'description' => 'Ramassage et compostage des restes alimentaires.',
                'price' => 2000,
            ],
            [
                'name' => 'Collecte de déchets encombrants',
                'description' => 'Ramassage des meubles et appareils encombrants.',
                'price' => 5000,
            ],
            [
                'name' => 'Nettoyage de poubelles',
                'description' => 'Lavage et désinfection des containers.',
                'price' => 3000,
            ],
            [
                'name' => 'Service express',
                'description' => 'Collecte rapide sous 24 heures.',
                'price' => 4000,
            ],
            [
                'name' => 'Service hebdomadaire',
                'description' => 'Collecte régulière selon un calendrier fixe.',
                'price' => 6000,
            ],
            [
                'name' => 'Collecte pour entreprises',
                'description' => 'Service adapté aux bureaux et commerces.',
                'price' => 8000,
            ],
            [
                'name' => 'Collecte écologique',
                'description' => 'Tri avancé et orientation vers le recyclage ou le compostage.',
                'price' => 3500,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
