<?php

namespace App\Filament\Resources\Briefs\Pages;

use App\Filament\Resources\Briefs\BriefResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBrief extends EditRecord
{
    protected static string $resource = BriefResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
