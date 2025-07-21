<?php

namespace App\Filament\Resources\OrderManagementResource\Pages;

use App\Filament\Resources\OrderManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrderManagement extends ViewRecord
{
    protected static string $resource = OrderManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
