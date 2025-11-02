<?php

namespace App\Filament\Resources\Briefs\Tables;

use App\Helpers\DownloadActions;
use App\Models\Brief;
use App\Services\BriefGenerator;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BriefsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('status')->badge()->colors([
                    'warning' => 'draft',
                    'info' => 'processing',
                    'success' => 'completed',
                ]),
                TextColumn::make('type')->badge(),
                TextColumn::make('raw_input')->limit(50)->lineClamp(2),
                TextColumn::make('structured_output')
                    ->words(10),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // UX Wise I'll disable it for now

                // Action::make('generate')
                //     ->label(fn ($record) => $record->status === 'completed' ? 'Regenerate with AI' : 'Generate with AI')
                //     ->icon('heroicon-o-sparkles')
                //     ->requiresConfirmation()
                //     ->action(function (Brief $record) {
                //         $rawInput = $record->raw_input;
                //         $type = $record->type;

                //         if (blank($rawInput) || blank($type)) {
                //             return;
                //         }

                //         $result = app(BriefGenerator::class)->generate($rawInput, $type);

                //         $record->update([
                //             'structured_output' => $result,
                //             'status' => 'completed',
                //         ]);
                //     }),
                ActionGroup::make([
                    ActionGroup::make([
                        ViewAction::make(),
                        EditAction::make(),
                    ])->dropdown(false),
                    ActionGroup::make([
                        DownloadActions::make('md'),
                        DownloadActions::make('txt'),
                        DownloadActions::make('pdf'),
                    ])->dropdown(false),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
