<?php

namespace App\Filament\Resources\LeaderboardDesignResource\Pages;

use App\Filament\Resources\LeaderboardDesignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaderboardDesign extends EditRecord
{
    protected static string $resource = LeaderboardDesignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
