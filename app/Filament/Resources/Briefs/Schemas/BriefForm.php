<?php

namespace App\Filament\Resources\Briefs\Schemas;

use App\Rules\MinWords;
use App\Services\BriefGenerator;
use Filament\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BriefForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(1)->schema([

            Wizard::make([
                Step::make('Basic Info')
                    ->icon(Heroicon::DocumentText)
                    ->description('Provide basic details about the brief.')
                    ->schema([
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
                            ->label('Brief Status'),

                        Textarea::make('notes')
                            ->label('Personal Notes')
                            ->placeholder('Any additional notes or thoughts? maybe tools to use, etc.')
                            ->nullable()
                            ->rows(6),
                    ]),

                Step::make('Client Notes')
                    ->icon(Heroicon::ClipboardDocumentList)
                    ->description('Enter the original client notes here.')
                    ->schema([
                        Textarea::make('raw_input')
                            ->label('Client Notes')
                            ->placeholder('Paste messy notes here...')
                            ->required()
                            ->rules([new MinWords(10)])
                            ->rows(8),
                    ]),

                Step::make('Generated Brief')
                    ->icon(Heroicon::OutlinedSparkles)
                    ->description('Review the automatically generated brief here.')
                    ->schema([
                        Action::make('generateBrief')
                            ->label('Generate Structured Output')
                            ->icon('heroicon-o-sparkles')
                            ->hidden(fn (string $context) => $context === 'view')
                            ->action(function (Get $get, Set $set) {
                                $rawInput = $get('raw_input');
                                $type = $get('type');

                                if (blank($rawInput)) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Unable to generate structured brief')
                                        ->body('Please fill in the Client Notes field before generating.')
                                        ->send();

                                    return;
                                }

                                if (blank($type)) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Unable to generate structured brief')
                                        ->body('Please select a Type for the brief before generating.')
                                        ->send();

                                    return;
                                }

                                if (str_word_count($rawInput) < 10) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Unable to generate structured brief')
                                        ->body('Please fill in at least 10 words in the Client Notes field before generating.')
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

                        MarkdownEditor::make('structured_output')
                            ->label('Generated Brief')
                            ->reactive()
                            ->dehydrated(true)
                            ->toolbarButtons([
                                ['bold', 'italic', 'strike', 'link'],
                                ['heading'],
                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                ['table'],
                                ['undo', 'redo'],
                            ]),
                    ]),

                Step::make('Preview & Edit')
                    ->icon(Heroicon::OutlinedEye)
                    ->description('Review and edit the full brief before submission.')
                    ->schema([
                        Grid::make()->columns(3)->schema([
                            Grid::make()->columnSpan(1)->columns(1)->schema([
                                Section::make('Brief Info')
                                    ->description('The brief’s title, type, status, and personal notes.')
                                    ->schema([
                                        TextInput::make('title')->label('Brief Title'),
                                        Select::make('type')->options([
                                            'Branding' => 'Branding',
                                            'Marketing' => 'Marketing',
                                            'Web App' => 'Web App',
                                            'General' => 'General',
                                        ])->label('Brief Type'),
                                        Select::make('status')->options([
                                            'draft' => 'Draft',
                                            'processing' => 'Processing',
                                            'completed' => 'Completed',
                                        ])->label('Brief Status'),
                                        Textarea::make('notes')
                                            ->label('Personal Notes')
                                            ->rows(6),
                                    ]),
                            ]),

                            Grid::make()->columnSpan(2)->columns(1)->schema([
                                Section::make('Client Notes')
                                    ->description('Original client input.')
                                    ->schema([
                                        Textarea::make('raw_input')
                                            ->label('Client Notes')
                                            ->rows(6),
                                    ]),

                                Section::make('Generated Brief')
                                    ->description('Automatically generated structured brief.')
                                    ->schema([
                                        MarkdownEditor::make('structured_output')
                                            ->label('Generated Brief')
                                            ->reactive()
                                            ->dehydrated(true)
                                            ->toolbarButtons([
                                                ['bold', 'italic', 'strike', 'link'],
                                                ['heading'],
                                                ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                                ['table'],
                                                ['undo', 'redo'],
                                            ]),
                                    ]),
                            ]),
                        ]),
                    ]),

            ]),

        ]);
    }
}
