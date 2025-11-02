<?php

namespace App\Filament\Resources\Briefs\Pages;

use App\Filament\Resources\Briefs\BriefResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBrief extends ViewRecord
{
    protected static string $resource = BriefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
