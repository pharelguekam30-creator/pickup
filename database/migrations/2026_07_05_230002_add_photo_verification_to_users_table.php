<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo', 255)->nullable()->after('phone');
            $table->string('verification_code', 6)->nullable()->after('photo');
            $table->timestamp('phone_verified_at')->nullable()->after('verification_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['photo', 'verification_code', 'phone_verified_at']);
        });
    }
};
