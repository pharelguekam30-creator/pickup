<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) $table->string('phone')->nullable();
            if (!Schema::hasColumn('users', 'country')) $table->string('country')->nullable();
            if (!Schema::hasColumn('users', 'region')) $table->string('region')->nullable();
            if (!Schema::hasColumn('users', 'city')) $table->string('city')->nullable();
            if (!Schema::hasColumn('users', 'quarter')) $table->string('quarter')->nullable();
            if (!Schema::hasColumn('users', 'address')) $table->string('address')->nullable();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) $table->dropColumn('phone');
            if (Schema::hasColumn('users', 'country')) $table->dropColumn('country');
            if (Schema::hasColumn('users', 'region')) $table->dropColumn('region');
            if (Schema::hasColumn('users', 'city')) $table->dropColumn('city');
            if (Schema::hasColumn('users', 'quarter')) $table->dropColumn('quarter');
            if (Schema::hasColumn('users', 'address')) $table->dropColumn('address');
        });
    }
};
