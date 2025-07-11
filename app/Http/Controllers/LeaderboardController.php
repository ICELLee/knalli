<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\LeaderboardSetting;
use App\Models\LeaderboardEntry;
use Illuminate\Support\Facades\Log;

class LeaderboardController extends Controller
{
    public function show()
    {
        $leaderboard = LeaderboardSetting::where('status', 'running')->latest()->first();
        $entries = [];

        if ($leaderboard) {
            $entries = LeaderboardEntry::where('leaderboard_setting_id', $leaderboard->id)
                ->orderByDesc('wagered')
                ->get();
        }

        return view('pages.leaderboard.show', compact('leaderboard', 'entries'));
    }

    public function fetchAndStoreEntries()
    {
        $leaderboard = LeaderboardSetting::where('status', 'running')->latest()->first();

        if (!$leaderboard) {
            Log::error('Kein aktives Leaderboard gefunden');
            return response()->json(['error' => 'Kein aktives Leaderboard'], 404);
        }

        $startDate = \Carbon\Carbon::parse($leaderboard->start_date)->toIso8601String();
        $endDate   = \Carbon\Carbon::parse($leaderboard->end_date)->toIso8601String();

        try {
            // API Anfrage senden
            $response = Http::withToken(config('services.roobet.api_key'))
                ->get('https://roobetconnect.com/affiliate/v2/stats', [
                    'userId'         => config('services.roobet.user_id'),
                    'startTime'      => $startDate,
                    'endTime'        => $endDate,
                    'categories'     => $leaderboard->categories ?: null,
                    'providers'      => $leaderboard->providers ?: null,
                    'gameIdentifiers'=> $leaderboard->game_identifiers ?: null,
                ]);
        } catch (\Throwable $e) {
            Log::error('Fehler bei API-Anfrage: ' . $e->getMessage());
            return response()->json(['error' => 'Fehler bei der API-Anfrage'], 500);
        }

        if ($response->failed()) {
            Log::error('API Fehler', [
                'response' => $response->body(),
                'status' => $response->status(),
            ]);
            return response()->json(['error' => 'API-Anfrage fehlgeschlagen'], 500);
        }

        try {
            $data = $response->json();
            Log::debug('API-Daten:', $data);

            LeaderboardEntry::where('leaderboard_setting_id', $leaderboard->id)->delete();

            foreach (array_chunk($data, 100) as $chunk) {
                foreach ($chunk as $entry) {
                    LeaderboardEntry::create([
                        'leaderboard_setting_id' => $leaderboard->id,
                        'uid'                    => $entry['uid'] ?? \Str::uuid(),
                        'username'               => $entry['username'] ?? 'Unbekannt',
                        'wagered'                => $entry['wagered'] ?? 0,
                        'weighted_wagered'       => $entry['weightedWagered'] ?? 0,
                        'favorite_game_id'       => $entry['favoriteGameId'] ?? null,
                        'favorite_game_title'    => $entry['favoriteGameTitle'] ?? null,
                    ]);
                }
            }

            return response()->json(['success' => true, 'count' => count($data)]);
        } catch (\Throwable $e) {
            Log::error('Fehler bei der Datenverarbeitung: ' . $e->getMessage());
            return response()->json(['error' => 'Fehler bei der Datenverarbeitung'], 500);
        }
    }

}
