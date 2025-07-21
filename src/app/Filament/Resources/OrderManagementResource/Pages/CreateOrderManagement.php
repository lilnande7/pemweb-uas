<?php

namespace App\Filament\Resources\OrderManagementResource\Pages;

use App\Filament\Resources\OrderManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderManagement extends CreateRecord
{
    protected static string $resource = OrderManagementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id() ?? 1;
        
        // Calculate subtotal from rental order items
        $subtotal = 0;
        if (isset($data['rentalOrderItems']) && is_array($data['rentalOrderItems'])) {
            foreach ($data['rentalOrderItems'] as $item) {
                if (isset($item['total_price'])) {
                    $subtotal += (float) $item['total_price'];
                }
            }
        }
        
        $data['subtotal'] = $subtotal;
        $taxAmount = $data['tax_amount'] ?? 0;
        $data['total_amount'] = $subtotal + $taxAmount;
        
        return $data;
    }
}
