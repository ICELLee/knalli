<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\LeaderboardDesign;
use App\Models\LeaderboardSetting;
use App\Models\LeaderboardEntry;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    // Neue Methode, zieht und speichert die API-Daten
    protected function updateEntries(): void
    {
        $leaderboard = LeaderboardSetting::where('status', 'running')->latest()->first();
        if (! $leaderboard) {
            \Log::error('Kein aktives Leaderboard gefunden');
            return;
        }

        // Zeitfenster in UTC-Zulu-Format
        $startDateUtc = Carbon::createFromFormat('d.m.Y H:i:s', '06.07.2025 17:17:40', 'Europe/Berlin')
            ->setTimezone('UTC')
            ->toIso8601ZuluString();
        $endDateUtc = Carbon::createFromFormat('d.m.Y H:i:s', '03.08.2025 17:17:43', 'Europe/Berlin')
            ->setTimezone('UTC')
            ->toIso8601ZuluString();

        $query = array_filter([
            'userId'          => config('services.roobet.user_id'),
            'startDate'       => $startDateUtc,
            'endDate'         => $endDateUtc,
            'categories'      => $leaderboard->categories,
            'providers'       => $leaderboard->providers,
            'gameIdentifiers' => $leaderboard->game_identifiers,
        ], fn($v) => $v !== null && $v !== '');

        $response = Http::withToken(config('services.roobet.api_key'))
            ->get('https://roobetconnect.com/affiliate/v2/stats', $query);

        if ($response->failed()) {
            \Log::error('Roobet API-Fehler', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);
            return;
        }

        $data = $response->json();

        // Vorherige Einträge löschen und neu speichern
        LeaderboardEntry::where('leaderboard_setting_id', $leaderboard->id)->delete();
        foreach (array_chunk($data, 100) as $chunk) {
            foreach ($chunk as $entry) {
                LeaderboardEntry::create([
                    'leaderboard_setting_id' => $leaderboard->id,
                    'uid'                    => $entry['uid'] ?? Str::uuid(),
                    'username'               => $entry['username'] ?? 'Unbekannt',
                    'wagered'                => $entry['wagered'] ?? 0,
                    'weighted_wagered'       => $entry['weightedWagered'] ?? 0,
                    'favorite_game_id'       => $entry['favoriteGameId'] ?? null,
                    'favorite_game_title'    => $entry['favoriteGameTitle'] ?? null,
                ]);
            }
        }

        \Log::debug('LeaderboardEntries aktualisiert', ['count' => count($data)]);
    }

    public function show()
    {
        // 1) Daten aktuell aus der API holen
        $this->updateEntries();

        // 2) Design laden
        $design = LeaderboardDesign::first();

        // 3) Aktives Leaderboard
        $leaderboard = LeaderboardSetting::where('status', 'running')
            ->latest()
            ->first();

        // 4) Einträge aus DB
        $entries = collect();
        if ($leaderboard) {
            $entries = LeaderboardEntry::where('leaderboard_setting_id', $leaderboard->id)
                ->orderByDesc('wagered')
                ->get();
        }

        // 5) View rendern
        return view('pages.leaderboard.show', compact(
            'design',
            'leaderboard',
            'entries',
        ));
    }
}
