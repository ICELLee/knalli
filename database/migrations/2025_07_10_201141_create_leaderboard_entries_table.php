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
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leaderboard_setting_id')->constrained('leaderboard_settings')->cascadeOnDelete();
            $table->uuid('uid');
            $table->string('username');
            $table->decimal('wagered', 15, 2)->default(0);
            $table->decimal('weighted_wagered', 15, 2)->default(0);
            $table->string('favorite_game_id')->nullable();
            $table->string('favorite_game_title')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_entries');
    }
};
