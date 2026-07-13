<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->date('current_month_start')->nullable()->after('status');
            $table->date('current_month_end')->nullable()->after('current_month_start');
            $table->enum('month_status', ['active', 'completed_vidangeur', 'awaiting_client', 'paid', 'disputed'])->nullable()->after('current_month_end');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['month_status', 'current_month_start', 'current_month_end']);
        });
    }
};
