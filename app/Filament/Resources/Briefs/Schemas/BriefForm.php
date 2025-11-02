<?php

namespace App\Filament\Resources\Briefs\Schemas;

use App\Services\BriefGenerator;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class BriefForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make()->columnSpan(1)->columns(1)->schema([
                TextInput::make('title')
                    ->label('Brief Title')
                    ->placeholder('e.g. Gym Brand Refresh')
                    ->required(),

                Select::make('type')
                    ->options([
                        'Branding' => 'Branding',
                        'Marketing' => 'Marketing',
                        'Web App' => 'Web App',
                        'General' => 'General',
                    ])
                    ->default('General')
                    ->label('Brief Type'),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                    ])
                    ->default('draft')
                    ->label('Brief Status')
                    ->disabled(),

                Action::make('generateBrief')
                    ->label('Generate Structured Output')
                    ->icon('heroicon-o-sparkles')
                    ->hidden(fn (string $context) => $context === 'view')
                    ->action(function (Get $get, Set $set) {
                        $rawInput = $get('raw_input');
                        $type = $get('type');

                        if (blank($rawInput) || blank($type) || (str_word_count($rawInput) < 10)) {
                            Notification::make()
                                ->danger()
                                ->title('Unable to generate structured brief')
                                ->body('Please fill in both the Client Notes and Type fields before generating..')
                                ->send();

                            return;

                        }

                        $generated = app(BriefGenerator::class)
                            ->generate($rawInput, $type);

                        $set('structured_output', $generated);

                        $set('status', 'completed');

                        Notification::make()
                            ->success()
                            ->title('Structured brief generated successfully!')
                            ->body('Please review the generated brief.')
                            ->send();

                    })
                    ->color('primary'),
            ]),

            Grid::make()->columnSpan(2)->columns(1)->schema([
                Textarea::make('raw_input')
                    ->label('Client Notes')
                    ->placeholder('Paste messy notes here...')
                    ->required()
                    ->rows(8),

                MarkdownEditor::make('structured_output')
                    ->label('Generated Brief')
                    // ->disabled() // prevents user edits but allows dynamic updates
                    ->reactive() // makes sure it updates when $set() changes it
                    ->dehydrated(true) // optional: prevents saving this value prematurely
                    ->toolbarButtons([
                        ['bold', 'italic', 'strike', 'link'],
                        ['heading'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table'],
                        ['undo', 'redo'],
                    ]),
                // ->toolbarButtons(['preview']), // keep it simple for read-only
            ]),

        ])->columns(3);
    }
}
