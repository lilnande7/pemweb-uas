<?php

namespace App\Filament\Resources\InstrumentCategoryResource\Pages;

use App\Filament\Resources\InstrumentCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInstrumentCategory extends ViewRecord
{
    protected static string $resource = InstrumentCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
