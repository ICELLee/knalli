<?php

namespace App\Filament\Resources\LeaderboardDesignResource\Pages;

use App\Filament\Resources\LeaderboardDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaderboardDesigns extends ListRecords
{
    protected static string $resource = LeaderboardDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
