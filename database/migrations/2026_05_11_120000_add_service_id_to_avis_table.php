<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            // Ajouter la colonne service_id si elle n'existe pas
            if (!Schema::hasColumn('avis', 'service_id')) {
                $table->foreignId('service_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avis', function (Blueprint $table) {
            if (Schema::hasColumn('avis', 'service_id')) {
                $table->dropConstrainedForeignId('service_id');
                $table->dropColumn('service_id');
            }
        });
    }
};
