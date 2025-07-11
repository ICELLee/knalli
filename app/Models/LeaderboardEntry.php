<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    protected $fillable = [
        'leaderboard_setting_id',
        'uid',
        'username',
        'wagered',
        'weighted_wagered',
        'favorite_game_id',
        'favorite_game_title',
    ];

    public function leaderboard()
    {
        return $this->belongsTo(LeaderboardSetting::class);
    }
}
