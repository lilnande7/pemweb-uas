<?php

namespace App\Filament\Resources\InstrumentCategoryResource\Pages;

use App\Filament\Resources\InstrumentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInstrumentCategories extends ListRecords
{
    protected static string $resource = InstrumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
