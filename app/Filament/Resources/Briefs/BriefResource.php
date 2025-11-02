<?php

namespace App\Filament\Resources\Briefs;

use App\Filament\Resources\Briefs\Pages\CreateBrief;
use App\Filament\Resources\Briefs\Pages\EditBrief;
use App\Filament\Resources\Briefs\Pages\ListBriefs;
use App\Filament\Resources\Briefs\Pages\ViewBrief;
use App\Filament\Resources\Briefs\Schemas\BriefForm;
use App\Filament\Resources\Briefs\Schemas\BriefInfolist;
use App\Filament\Resources\Briefs\Tables\BriefsTable;
use App\Models\Brief;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BriefResource extends Resource
{
    protected static ?string $model = Brief::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BriefForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BriefInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BriefsTable::configure($table);
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
            'index' => ListBriefs::route('/'),
            'create' => CreateBrief::route('/create'),
            'view' => ViewBrief::route('/{record}'),
            'edit' => EditBrief::route('/{record}/edit'),
        ];
    }
}
