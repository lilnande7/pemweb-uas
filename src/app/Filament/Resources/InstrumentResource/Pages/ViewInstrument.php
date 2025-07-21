<?php

namespace App\Filament\Resources\InstrumentResource\Pages;

use App\Filament\Resources\InstrumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInstrument extends ViewRecord
{
    protected static string $resource = InstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
