<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstrumentResource\Pages;
use App\Models\Instrument;
use App\Models\InstrumentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InstrumentResource extends Resource
{
    protected static ?string $model = Instrument::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    protected static ?string $navigationGroup = 'Instrument Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('brand')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('model')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(InstrumentCategory::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Images')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->image()
                            ->multiple()
                            ->directory('instruments')
                            ->imageEditor()
                            ->reorderable()
                            ->maxFiles(5)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('daily_rate')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000),
                        
                        Forms\Components\TextInput::make('weekly_rate')
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000),
                        
                        Forms\Components\TextInput::make('monthly_rate')
                            ->numeric()
                            ->prefix('Rp')
                            ->step(1000),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Inventory & Condition')
                    ->schema([
                        Forms\Components\TextInput::make('quantity_total')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $available = $get('quantity_available') ?? 0;
                                if ($state < $available) {
                                    $set('quantity_available', $state);
                                }
                            }),
                        
                        Forms\Components\TextInput::make('quantity_available')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->minValue(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $total = $get('quantity_total') ?? 1;
                                if ($state > $total) {
                                    $set('quantity_available', $total);
                                }
                            }),
                        
                        Forms\Components\Select::make('condition')
                            ->options([
                                'excellent' => 'Excellent',
                                'good' => 'Good',
                                'fair' => 'Fair',
                                'poor' => 'Poor',
                            ])
                            ->required()
                            ->default('good'),
                        
                        Forms\Components\TextInput::make('year_made')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        
                        Forms\Components\TextInput::make('serial_number')
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_available')
                            ->default(true)
                            ->helperText('Available for rental'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Active in system'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Specifications')
                    ->schema([
                        Forms\Components\KeyValue::make('specifications')
                            ->keyLabel('Property')
                            ->valueLabel('Value')
                            ->addActionLabel('Add specification')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->getStateUsing(fn ($record) => $record->images[0] ?? null)
                    ->size(60)
                    ->circular(),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                
                Tables\Columns\TextColumn::make('brand')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('daily_rate')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('quantity_available')
                    ->label('Available')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                
                Tables\Columns\TextColumn::make('quantity_total')
                    ->label('Total')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'excellent' => 'success',
                        'good' => 'primary',
                        'fair' => 'warning',
                        'poor' => 'danger',
                    }),
                
                Tables\Columns\IconColumn::make('is_available')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Category')
                    ->options(InstrumentCategory::pluck('name', 'id'))
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('condition')
                    ->options([
                        'excellent' => 'Excellent',
                        'good' => 'Good',
                        'fair' => 'Fair',
                        'poor' => 'Poor',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_available')
                    ->label('Available')
                    ->boolean()
                    ->trueLabel('Available only')
                    ->falseLabel('Unavailable only'),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListInstruments::route('/'),
            'create' => Pages\CreateInstrument::route('/create'),
            'view' => Pages\ViewInstrument::route('/{record}'),
            'edit' => Pages\EditInstrument::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::available()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
