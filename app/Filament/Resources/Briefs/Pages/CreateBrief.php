<?php

namespace App\Filament\Resources\Briefs\Pages;

use App\Filament\Resources\Briefs\BriefResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBrief extends CreateRecord
{
    protected static string $resource = BriefResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }
}
