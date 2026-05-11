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
            if (!Schema::hasColumn('avis', 'service_id')) {
                $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('cascade');
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
            }
        });
    }
};
