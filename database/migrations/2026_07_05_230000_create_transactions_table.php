<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type', 50)->comment('depot,retrait,paiement,commission,remboursement');
            $table->decimal('montant', 10, 2);
            $table->decimal('solde_avant', 10, 2)->default(0);
            $table->decimal('solde_apres', 10, 2)->default(0);
            $table->string('methode', 50)->nullable()->comment('om,momo,carte,interne');
            $table->string('reference', 255)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->nullOnDelete();
            $table->string('statut', 50)->default('completed');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
