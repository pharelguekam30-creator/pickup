<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CollectionPlan;

class CollectionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Familial 1x/semaine',
                'description' => 'Une collecte par semaine, idéal pour les petits ménages. Passage chaque semaine le jour de votre choix.',
                'type' => 'familial',
                'collections_per_week' => 1,
                'collection_days' => ['monday', 'wednesday', 'friday'],
                'price_per_month' => 10000,
            ],
            [
                'name' => 'Familial 2x/semaine',
                'description' => 'Deux collectes par semaine pour les familles nombreuses. Passage le lundi et le jeudi.',
                'type' => 'familial',
                'collections_per_week' => 2,
                'collection_days' => ['monday', 'thursday'],
                'price_per_month' => 15000,
            ],
            [
                'name' => 'Familial 3x/semaine',
                'description' => 'Collecte trois fois par semaine. Passage le lundi, mercredi et vendredi.',
                'type' => 'familial',
                'collections_per_week' => 3,
                'collection_days' => ['monday', 'wednesday', 'friday'],
                'price_per_month' => 20000,
            ],
            [
                'name' => 'Entreprise 2x/semaine',
                'description' => 'Service adapté aux bureaux et commerces. Deux collectes par semaine.',
                'type' => 'entreprise',
                'collections_per_week' => 2,
                'collection_days' => ['monday', 'thursday'],
                'price_per_month' => 25000,
            ],
            [
                'name' => 'Entreprise 3x/semaine',
                'description' => 'Pour les entreprises à forte production de déchets. Passage trois fois par semaine.',
                'type' => 'entreprise',
                'collections_per_week' => 3,
                'collection_days' => ['monday', 'wednesday', 'friday'],
                'price_per_month' => 35000,
            ],
            [
                'name' => 'Entreprise 5x/semaine',
                'description' => 'Collecte quotidienne du lundi au vendredi. Service premium pour grandes entreprises.',
                'type' => 'entreprise',
                'collections_per_week' => 5,
                'collection_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'price_per_month' => 50000,
            ],
        ];

        foreach ($plans as $plan) {
            CollectionPlan::create($plan);
        }
    }
}
