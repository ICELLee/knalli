<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderboardSettingResource\Pages;
use App\Filament\Resources\LeaderboardSettingResource\RelationManagers;
use App\Models\LeaderboardSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
class LeaderboardSettingResource extends Resource
{
    protected static ?string $model = LeaderboardSetting::class;


    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Leaderboard Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'paused'  => 'Paused',
                        'stopped' => 'Stopped',
                    ])
                    ->required(),
                Textarea::make('prize_info')
                    ->label('Prize Information')
                    ->rows(3),
                TextInput::make('first_place_reward')
                    ->label('First Place Reward'),
                TextInput::make('second_place_reward')
                    ->label('Second Place Reward'),
                TextInput::make('third_place_reward')
                    ->label('Third Place Reward'),
                Textarea::make('game_identifiers')
                    ->label('Game Identifiers')
                    ->rows(2),
                Textarea::make('categories')
                    ->label('Categories')
                    ->rows(2),
                Textarea::make('providers')
                    ->label('Providers')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label('ID'),
                TextColumn::make('start_date')->dateTime('Y-m-d H:i')->sortable()->label('Start'),
                TextColumn::make('end_date')->dateTime('Y-m-d H:i')->sortable()->label('End'),
                TextColumn::make('status')->sortable()->label('Status'),
                TextColumn::make('entries_count')
                    ->counts('entries')
                    ->label('Entries'),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaderboardSettings::route('/'),
            'create' => Pages\CreateLeaderboardSetting::route('/create'),
            'edit' => Pages\EditLeaderboardSetting::route('/{record}/edit'),
        ];
    }
}
