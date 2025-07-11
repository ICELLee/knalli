<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\LeaderboardController;

Route::get('/', [LeaderboardController::class, 'show'])->name('leaderboard.show');

Route::get('/leaderboard/fetch', [LeaderboardController::class, 'fetchAndStoreEntries']);

