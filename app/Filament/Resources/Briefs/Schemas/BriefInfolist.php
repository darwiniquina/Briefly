<?php

namespace App\Filament\Resources\Briefs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BriefInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()->columnSpan(1)->columns(1)->schema([
                Section::make('Brief Info')
                    ->description('A concise summary of the brief, including title, type, status, and personal notes.')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Brief Title'),
                        TextEntry::make('type')
                            ->label('Brief Type'),
                        TextEntry::make('status')
                            ->label('Brief Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => Str::title($state))
                            ->color(fn (string $state): string => match ($state) {
                                'draft' => 'gray',
                                'processing' => 'warning',
                                'completed' => 'success',
                            }),

                        TextEntry::make('notes')
                            ->label('Personal Notes'),
                    ]),
            ]),

            Grid::make()->columnSpan(2)->columns(1)->schema([
                Section::make('Client Notes')
                    ->description('A concise summary of the brief, including title, type, status, and personal notes.')
                    ->schema([
                        TextEntry::make('raw_input')
                            ->label('Client Notes'),

                    ]),

                Section::make('Generated Brief')
                    ->description('The structured brief generated automatically, ready for review or sharing.')
                    ->schema([
                        TextEntry::make('structured_output')
                            ->label('Generated Brief')
                            ->copyable()
                            ->markdown(),
                    ]),
            ]),

        ])->columns(3);
    }
}
