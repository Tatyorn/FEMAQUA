<?php

namespace App\Filament\Resources\Tools\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ToolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('link')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->multiple()
                    ->preload(),
            ]);
    }
}
