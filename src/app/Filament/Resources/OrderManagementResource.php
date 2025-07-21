<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderManagementResource\Pages;
use App\Filament\Resources\OrderManagementResource\RelationManagers;
use App\Models\RentalOrder;
use App\Models\Customer;
use App\Models\Instrument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrderManagementResource extends Resource
{
    protected static ?string $model = RentalOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Rental Orders';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Section::make('Order Information')->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'ORD-' . date('Y') . '-' . str_pad(RentalOrder::count() + 1, 3, '0', STR_PAD_LEFT))
                            ->maxLength(255),

                        Forms\Components\Hidden::make('user_id')
                            ->default(fn () => auth()->id() ?? 1),

                        Forms\Components\Select::make('customer_id')
                            ->label('Customer')
                            ->options(\App\Models\Customer::selectRaw("id, CONCAT(first_name, ' ', last_name) as name")->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->placeholder('Select a customer')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('address')
                                    ->required(),
                                Forms\Components\TextInput::make('city')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('postal_code')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('id_card_number')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->createOptionUsing(function (array $data) {
                                $customer = \App\Models\Customer::create([
                                    'first_name' => $data['first_name'],
                                    'last_name' => $data['last_name'],
                                    'email' => $data['email'],
                                    'phone' => $data['phone'],
                                    'address' => $data['address'],
                                    'city' => $data['city'],
                                    'postal_code' => $data['postal_code'],
                                    'id_card_number' => $data['id_card_number'],
                                    'status' => 'active',
                                ]);
                                
                                return $customer->id;
                            }),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'active' => 'Active',
                                'returned' => 'Returned',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columnSpan(1),

                    Section::make('Rental Details')->schema([
                        Forms\Components\DatePicker::make('rental_start_date')
                            ->label('Rental Start Date')
                            ->required()
                            ->native(false)
                            ->minDate(now()->toDateString())
                            ->closeOnDateSelection(true),

                        Forms\Components\DatePicker::make('rental_end_date')
                            ->label('Rental End Date')
                            ->required()
                            ->native(false)
                            ->minDate(fn (Forms\Get $get) => $get('rental_start_date') ?: now()->toDateString())
                            ->closeOnDateSelection(true),

                        Forms\Components\DatePicker::make('actual_return_date')
                            ->label('Actual Return Date')
                            ->native(false)
                            ->closeOnDateSelection(true),
                    ])->columnSpan(1),
                ]),

                Section::make('Instruments')->schema([
                    Forms\Components\Repeater::make('rentalOrderItems')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('instrument_id')
                                ->label('Instrument')
                                ->relationship('instrument', 'name')
                                ->searchable(['name', 'brand', 'model'])
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, $state) {
                                    if ($state) {
                                        $instrument = \App\Models\Instrument::find($state);
                                        if ($instrument) {
                                            $set('unit_price', $instrument->daily_rate);
                                        }
                                    }
                                })
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->minValue(1)
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $rentalDays = $get('rental_days') ?? 1;
                                    $totalPrice = $state * $unitPrice * $rentalDays;
                                    $set('total_price', $totalPrice);
                                    
                                    // Update subtotal
                                    $items = $get('../../rentalOrderItems') ?? [];
                                    $subtotal = 0;
                                    foreach ($items as $item) {
                                        $subtotal += (float) ($item['total_price'] ?? 0);
                                    }
                                    $set('../../subtotal', $subtotal);
                                    
                                    // Update total amount
                                    $taxAmount = $get('../../tax_amount') ?? 0;
                                    $set('../../total_amount', $subtotal + $taxAmount);
                                })
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('unit_price')
                                ->label('Unit Price (Daily)')
                                ->numeric()
                                ->prefix('Rp')
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                    $quantity = $get('quantity') ?? 1;
                                    $rentalDays = $get('rental_days') ?? 1;
                                    $totalPrice = $quantity * $state * $rentalDays;
                                    $set('total_price', $totalPrice);
                                    
                                    // Update subtotal
                                    $items = $get('../../rentalOrderItems') ?? [];
                                    $subtotal = 0;
                                    foreach ($items as $item) {
                                        $subtotal += (float) ($item['total_price'] ?? 0);
                                    }
                                    $set('../../subtotal', $subtotal);
                                    
                                    // Update total amount
                                    $taxAmount = $get('../../tax_amount') ?? 0;
                                    $set('../../total_amount', $subtotal + $taxAmount);
                                })
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('rental_days')
                                ->label('Rental Days')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->minValue(1)
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                    $quantity = $get('quantity') ?? 1;
                                    $unitPrice = $get('unit_price') ?? 0;
                                    $totalPrice = $quantity * $unitPrice * $state;
                                    $set('total_price', $totalPrice);
                                    
                                    // Update subtotal
                                    $items = $get('../../rentalOrderItems') ?? [];
                                    $subtotal = 0;
                                    foreach ($items as $item) {
                                        $subtotal += (float) ($item['total_price'] ?? 0);
                                    }
                                    $set('../../subtotal', $subtotal);
                                    
                                    // Update total amount
                                    $taxAmount = $get('../../tax_amount') ?? 0;
                                    $set('../../total_amount', $subtotal + $taxAmount);
                                })
                                ->columnSpan(1),

                            Forms\Components\TextInput::make('total_price')
                                ->label('Total Price')
                                ->numeric()
                                ->prefix('Rp')
                                ->disabled()
                                ->dehydrated()
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                    // Update subtotal when any total_price changes
                                    $items = $get('../../rentalOrderItems') ?? [];
                                    $subtotal = 0;
                                    foreach ($items as $item) {
                                        $subtotal += (float) ($item['total_price'] ?? 0);
                                    }
                                    $set('../../subtotal', $subtotal);
                                    
                                    // Update total amount
                                    $taxAmount = $get('../../tax_amount') ?? 0;
                                    $set('../../total_amount', $subtotal + $taxAmount);
                                })
                                ->columnSpan(1),

                            Forms\Components\Select::make('condition_out')
                                ->label('Condition Out')
                                ->options([
                                    'excellent' => 'Excellent',
                                    'good' => 'Good',
                                    'fair' => 'Fair',
                                    'poor' => 'Poor',
                                ])
                                ->default('excellent')
                                ->required()
                                ->columnSpan(1),
                        ])
                        ->columns(6)
                        ->addActionLabel('Add Instrument')
                        ->defaultItems(1)
                        ->minItems(1)
                        ->maxItems(10),
                ]),

                Section::make('Financial Information')->schema([
                    Grid::make(3)->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->default(0),

                        Forms\Components\TextInput::make('tax_amount')
                            ->label('Tax Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0)
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
                                $subtotal = $get('subtotal') ?? 0;
                                $set('total_amount', $subtotal + $state);
                            }),

                        Forms\Components\TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->default(0),
                    ]),

                    Grid::make(2)->schema([
                        Forms\Components\TextInput::make('security_deposit')
                            ->label('Security Deposit')
                            ->numeric()
                            ->prefix('Rp')
                            ->default(0),

                        Forms\Components\Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                            ])
                            ->default('pending')
                            ->required(),
                    ]),
                ]),

                Section::make('Additional Information')->schema([
                    Forms\Components\Textarea::make('notes')
                        ->rows(3),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('customer_full_name')
                    ->label('Customer')
                    ->getStateUsing(function ($record) {
                        return $record->customer ? $record->customer->first_name . ' ' . $record->customer->last_name : 'N/A';
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rental_start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rental_end_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'confirmed',
                        'success' => 'active',
                        'success' => 'returned',
                        'danger' => 'overdue',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'active' => 'Active',
                        'returned' => 'Returned',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListOrderManagement::route('/'),
            'create' => Pages\CreateOrderManagement::route('/create'),
            'view' => Pages\ViewOrderManagement::route('/{record}'),
            'edit' => Pages\EditOrderManagement::route('/{record}/edit'),
        ];
    }
}
