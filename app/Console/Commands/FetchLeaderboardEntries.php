<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\LeaderboardController;

class FetchLeaderboardEntries extends Command
{
    protected $signature = 'leaderboard:fetch';
    protected $description = 'Holt und speichert die aktuellen Leaderboard-Einträge';

    public function handle()
    {
        // Die Methode erwartet eine HTTP-Context, du kannst sie direkt instanziieren:
        $controller = new LeaderboardController();
        $response = $controller->fetchAndStoreEntries();

        if ($response->status() !== 200) {
            $this->error('Fetch fehlgeschlagen: ' . json_encode($response->getData()));
        } else {
            $this->info('Erfolgreich ' . $response->getData()->count . ' Einträge geladen.');
        }
    }
}
