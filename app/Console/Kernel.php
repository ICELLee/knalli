protected function schedule(Schedule $schedule): void
{
$schedule->job(new \App\Jobs\SyncLeaderboardJob)->everyFiveMinutes();
}

protected function schedule(Schedule $schedule)
{
// Alle 30 Minuten
$schedule->command('leaderboard:fetch')
->everyThirtyMinutes()
->withoutOverlapping()
->onOneServer(); // Verhindert parallele Ausführungen in Multi-Server-Umgebung
}
