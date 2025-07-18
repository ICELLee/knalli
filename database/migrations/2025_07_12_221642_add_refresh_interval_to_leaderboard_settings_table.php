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
        Schema::table('leaderboard_settings', function (Blueprint $table) {
            $table->unsignedInteger('refresh_interval')->default(60)->after('status')
                ->comment('Refresh-Intervall in Sekunden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaderboard_settings', function (Blueprint $table) {
            $table->dropColumn('refresh_interval');
        });
    }
};
