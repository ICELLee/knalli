<?php

namespace App\Filament\Resources\LeaderboardSettingResource\Pages;

use App\Filament\Resources\LeaderboardSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeaderboardSetting extends EditRecord
{
    protected static string $resource = LeaderboardSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
