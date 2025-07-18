<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaderboardDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_color',
        'background_color',
        'button_color',
        'logo',
        'first_place_gif',
        'second_place_gif',
        'third_place_gif',
        'currency_symbol',
        'play_now_url',
        'rules_content',
    ];
}
