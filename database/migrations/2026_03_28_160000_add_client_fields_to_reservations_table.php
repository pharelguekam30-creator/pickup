<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('reservations', 'client_id')) {
                $table->foreignId('client_id')->nullable()->constrained('users')->nullOnDelete()->after('user_id');
            }
            if (!Schema::hasColumn('reservations', 'client_name')) {
                $table->string('client_name')->nullable()->after('client_id');
            }
        });
    }

    public function down(): void {
        Schema::table('reservations', function (Blueprint $table) {
            if (Schema::hasColumn('reservations', 'client_name')) {
                $table->dropColumn('client_name');
            }
            if (Schema::hasColumn('reservations', 'client_id')) {
                $table->dropForeign(['client_id']);
                $table->dropColumn('client_id');
            }
        });
    }
};