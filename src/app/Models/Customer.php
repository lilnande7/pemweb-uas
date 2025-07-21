<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'date_of_birth',
        'id_card_number',
        'id_card_image',
        'status',
        'security_deposit',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'security_deposit' => 'decimal:2',
    ];

    public function rentalOrders(): HasMany
    {
        return $this->hasMany(RentalOrder::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getTotalRentalsAttribute(): int
    {
        return $this->rentalOrders()->count();
    }

    public function getActiveRentalsAttribute(): int
    {
        return $this->rentalOrders()
            ->whereIn('status', ['confirmed', 'active'])
            ->count();
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->rentalOrders()
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
    }
}
