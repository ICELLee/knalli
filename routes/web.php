<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaderboardController;  // hier den Controller importieren
use App\Http\Controllers\AffiliateTestController;

Route::get('/', [LeaderboardController::class, 'show'])
    ->name('leaderboard.show');

Route::get('/leaderboard/fetch', [LeaderboardController::class, 'fetchAndStoreEntries'])
    ->name('leaderboard.fetch');

Route::get('/leaderboard/data', [LeaderboardController::class, 'showData']);
Route::match(['get','post'], '/debug/affiliate-test', [AffiliateTestController::class, 'handle'])
    ->name('debug.affiliate-test');
