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
        Schema::create('leaderboard_settings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['pending', 'running', 'paused', 'stopped'])->default('pending');
            $table->text('prize_info')->nullable();
            $table->string('first_place_reward')->nullable();
            $table->string('second_place_reward')->nullable();
            $table->string('third_place_reward')->nullable();
            $table->text('game_identifiers')->nullable();
            $table->text('categories')->nullable();
            $table->text('providers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_settings');
    }
};
