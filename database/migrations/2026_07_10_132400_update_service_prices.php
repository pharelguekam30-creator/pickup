<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')->where('name', 'Collecte de poubelles ménagères')->where('price', '<', 2000)->update(['price' => 2000]);
        DB::table('services')->where('name', 'Collecte de recyclables')->where('price', '<', 2500)->update(['price' => 2500]);
        DB::table('services')->where('name', 'Collecte de déchets organiques')->where('price', '<', 2000)->update(['price' => 2000]);
        DB::table('services')->where('name', 'Collecte de déchets encombrants')->where('price', '<', 5000)->update(['price' => 5000]);
        DB::table('services')->where('name', 'Nettoyage de poubelles')->where('price', '<', 3000)->update(['price' => 3000]);
        DB::table('services')->where('name', 'Service express')->where('price', '<', 4000)->update(['price' => 4000]);
        DB::table('services')->where('name', 'Service hebdomadaire')->where('price', '<', 6000)->update(['price' => 6000]);
        DB::table('services')->where('name', 'Collecte pour entreprises')->where('price', '<', 8000)->update(['price' => 8000]);
        DB::table('services')->where('name', 'Collecte écologique')->where('price', '<', 3500)->update(['price' => 3500]);
    }

    public function down(): void
    {
        DB::table('services')->where('name', 'Collecte de poubelles ménagères')->where('price', 2000)->update(['price' => 500]);
        DB::table('services')->where('name', 'Collecte de recyclables')->where('price', 2500)->update(['price' => 700]);
        DB::table('services')->where('name', 'Collecte de déchets organiques')->where('price', 2000)->update(['price' => 600]);
        DB::table('services')->where('name', 'Collecte de déchets encombrants')->where('price', 5000)->update(['price' => 1500]);
        DB::table('services')->where('name', 'Nettoyage de poubelles')->where('price', 3000)->update(['price' => 800]);
        DB::table('services')->where('name', 'Service express')->where('price', 4000)->update(['price' => 1200]);
        DB::table('services')->where('name', 'Service hebdomadaire')->where('price', 6000)->update(['price' => 2000]);
        DB::table('services')->where('name', 'Collecte pour entreprises')->where('price', 8000)->update(['price' => 2500]);
        DB::table('services')->where('name', 'Collecte écologique')->where('price', 3500)->update(['price' => 1000]);
    }
};
