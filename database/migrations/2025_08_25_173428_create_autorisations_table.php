<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('autorisations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // nom de l'autorisation
            $table->string('description')->nullable(); // description optionnelle
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('autorisations');
    }
};
