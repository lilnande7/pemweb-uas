<?php

namespace App\Filament\Resources\OrderManagementResource\Pages;

use App\Filament\Resources\OrderManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderManagement extends EditRecord
{
    protected static string $resource = OrderManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
