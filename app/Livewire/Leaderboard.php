<?php

namespace App\Livewire;

use Livewire\Component;

class Leaderboard extends Component
{
    public int $refreshInterval;

    public function mount()
    {
        // Hier NUR das aktive Setting laden, z.B. das gerade "running" ist
        $setting = LeaderboardSetting::where('status', 'running')->first();
        $this->refreshInterval = $setting
            ? $setting->refresh_interval
            : 60; // Fallback auf 60s
    }

    public function render()
    {
        // Hier deine Logik, z.B. Top 10 Einträge
        $entries = Entry::orderByDesc('points')->limit(10)->get();

        return view('livewire.leaderboard', [
            'entries' => $entries,
        ]);
    }
}
