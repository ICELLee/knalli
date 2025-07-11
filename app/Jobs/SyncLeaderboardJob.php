<?php

namespace App\Jobs;

use App\Models\LeaderboardSetting;
use App\Models\LeaderboardEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncLeaderboardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $leaderboard = LeaderboardSetting::where('status', 'running')->first();

        if (!$leaderboard) {
            Log::warning('❌ Kein aktives Leaderboard gefunden.');
            return;
        }

        $uid     = env('ROOBET_UID');
        $apiKey  = env('ROOBET_API_KEY');

        if (blank($uid) || blank($apiKey)) {
            Log::error('❌ UID oder API-Key aus .env fehlen.');
            return;
        }

        try {
            $response = Http::withToken($apiKey)->get('https://roobetconnect.com/affiliate/v2/stats', [
                'userId'     => $uid,
                'startDate'  => $leaderboard->start_date?->toIso8601String(),
                'endDate'    => $leaderboard->end_date?->toIso8601String(),
                'categories' => 'slots,provably fair',
            ]);

            if ($response->failed()) {
                Log::error('❌ API-Fehler: ' . $response->body());
                return;
            }

            $data = $response->json();

            foreach ($data as $entry) {
                LeaderboardEntry::updateOrCreate([
                    'leaderboard_setting_id' => $leaderboard->id,
                    'uid' => $entry['uid'],
                ], [
                    'username'            => $entry['username'] ?? 'Unbekannt',
                    'wagered'             => $entry['wagered'] ?? 0,
                    'weighted_wagered'    => $entry['weightedWagered'] ?? 0,
                    'favorite_game_id'    => $entry['favoriteGameId'] ?? null,
                    'favorite_game_title' => $entry['favoriteGameTitle'] ?? null,
                ]);
            }

            Log::info('✅ Leaderboard erfolgreich synchronisiert');
        } catch (\Throwable $e) {
            Log::error('❌ Leaderboard-Sync Crash: ' . $e->getMessage());
        }
    }
}
