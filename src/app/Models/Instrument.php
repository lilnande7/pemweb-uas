<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instrument extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'category_id',
        'description',
        'images',
        'daily_rate',
        'weekly_rate',
        'monthly_rate',
        'quantity_available',
        'quantity_total',
        'condition',
        'year_made',
        'serial_number',
        'is_available',
        'is_active',
        'specifications',
    ];

    protected $casts = [
        'images' => 'array',
        'specifications' => 'array',
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InstrumentCategory::class, 'category_id');
    }

    public function rentalOrderItems(): HasMany
    {
        return $this->hasMany(RentalOrderItem::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('is_active', true)
            ->where('quantity_available', '>', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRateForDays(int $days): float
    {
        if ($days >= 30 && $this->monthly_rate) {
            $months = ceil($days / 30);
            return $this->monthly_rate * $months;
        }

        if ($days >= 7 && $this->weekly_rate) {
            $weeks = ceil($days / 7);
            return $this->weekly_rate * $weeks;
        }

        return $this->daily_rate * $days;
    }

    public function updateAvailability(): void
    {
        $rentedQuantity = $this->rentalOrderItems()
            ->whereHas('rentalOrder', function ($query) {
                $query->whereIn('status', ['confirmed', 'active']);
            })
            ->sum('quantity');

        $this->quantity_available = $this->quantity_total - $rentedQuantity;
        $this->is_available = $this->quantity_available > 0;
        $this->save();
    }
}
