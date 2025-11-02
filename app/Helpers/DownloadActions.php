<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadActions
{
    public static function make($format = 'md'): Action
    {
        $labels = [
            'md' => 'Download Markdown',
            'txt' => 'Download Text',
            'pdf' => 'Download PDF',
        ];

        $icons = [
            'md' => 'heroicon-o-document-text',
            'txt' => 'heroicon-o-document',
            'pdf' => 'heroicon-o-document-arrow-down',
        ];

        return Action::make("download_$format")
            ->label($labels[$format] ?? 'Download')
            ->icon($icons[$format] ?? 'heroicon-o-arrow-down-tray')
            ->visible(fn ($record) => filled($record->structured_output))
            ->action(function ($record) use ($format): StreamedResponse {
                $filename = Str::slug($record->title ?? 'brief').".$format";

                if ($format === 'pdf') {
                    $html = Str::markdown($record->structured_output);
                    $pdf = Pdf::loadHTML($html);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->stream();
                    }, "$filename.pdf");
                }

                $contentType = match ($format) {
                    'md' => 'text/markdown',
                    'txt' => 'text/plain',
                    default => 'application/octet-stream',
                };

                return response()->streamDownload(function () use ($record) {
                    echo $record->structured_output;
                }, $filename, ['Content-Type' => $contentType]);
            });
    }
}
