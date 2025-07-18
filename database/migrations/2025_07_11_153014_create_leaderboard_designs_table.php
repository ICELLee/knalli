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
        Schema::create('leaderboard_designs', function (Blueprint $table) {
            $table->id();
            $table->string('header_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('button_color')->nullable();
            $table->string('logo')->nullable();
            $table->string('first_place_gif')->nullable();
            $table->string('second_place_gif')->nullable();
            $table->string('third_place_gif')->nullable();
            $table->string('currency_symbol', 2)->default('€');
            $table->string('play_now_url')->nullable();
            $table->text('rules_content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboard_designs');
    }
};
