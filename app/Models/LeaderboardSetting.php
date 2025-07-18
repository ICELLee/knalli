<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaderboardSetting extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'status',
        'prize_info',
        'first_place_reward',
        'second_place_reward',
        'third_place_reward',
        'game_identifiers',
        'categories',
        'providers',
        'refresh_interval',
        'third_place_reward',
        'prize_tiers',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'prize_tiers' => 'array',
    ];

    public function entries()
    {
        return $this->hasMany(LeaderboardEntry::class);
    }
}
