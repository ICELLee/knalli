<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaderboardDesignResource\Pages;
use App\Filament\Resources\LeaderboardDesignResource\RelationManagers;
use App\Models\LeaderboardDesign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaderboardDesignResource extends Resource
{
    protected static ?string $model = LeaderboardDesign::class;
    protected static ?string $navigationLabel = 'Leaderboard Design';
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Colors')
                    ->schema([
                        Forms\Components\ColorPicker::make('header_color')
                            ->label('Header Text Color'),
                        Forms\Components\ColorPicker::make('background_color')
                            ->label('Page Background Color'),
                        Forms\Components\ColorPicker::make('button_color')
                            ->label('Play Button Color'),
                        // weitere ColorPicker für jeden Bereich
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images & GIFs')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->label('Logo')
                            ->directory('leaderboard'),
                        Forms\Components\FileUpload::make('first_place_gif')
                            ->label('1st Place GIF')
                            ->directory('leaderboard'),
                        Forms\Components\FileUpload::make('second_place_gif')
                            ->label('2nd Place GIF')
                            ->directory('leaderboard'),
                        Forms\Components\FileUpload::make('third_place_gif')
                            ->label('3rd Place GIF')
                            ->directory('leaderboard'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Currency & Link')
                    ->schema([
                        Forms\Components\TextInput::make('currency_symbol')
                            ->label('Currency Symbol')
                            ->required()
                            ->maxLength(2),

                        Forms\Components\TextInput::make('play_now_url')
                            ->label('Play Now Button URL')
                            ->url()
                            ->required(),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Rules Content')
                    ->schema([
                        Forms\Components\RichEditor::make('rules_content')
                            ->label('Competition Rules')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'bulletList',
                                'numberList',
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Edited')
                    ->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListLeaderboardDesigns::route('/'),
            'create' => Pages\CreateLeaderboardDesign::route('/create'),
            'edit' => Pages\EditLeaderboardDesign::route('/{record}/edit'),
        ];
    }
}
