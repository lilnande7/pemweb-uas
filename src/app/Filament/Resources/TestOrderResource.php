<?php

namespace App\Filament\Resources;

use App\Models\RentalOrder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Pages\Page;
use Filament\Resources\Pages\ListRecords;

class TestOrderResource extends Resource
{
    protected static ?string $model = RentalOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Test Orders';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number'),
                Tables\Columns\TextColumn::make('status'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => TestOrderListPage::route('/'),
        ];
    }
}

class TestOrderListPage extends ListRecords
{
    protected static string $resource = TestOrderResource::class;
}
