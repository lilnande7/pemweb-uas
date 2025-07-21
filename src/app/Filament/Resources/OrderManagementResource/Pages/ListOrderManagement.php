<?php

namespace App\Filament\Resources\OrderManagementResource\Pages;

use App\Filament\Resources\OrderManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderManagement extends ListRecords
{
    protected static string $resource = OrderManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
