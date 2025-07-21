<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class RentalOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'user_id',
        'rental_start_date',
        'rental_end_date',
        'actual_return_date',
        'status',
        'subtotal',
        'tax_amount',
        'security_deposit',
        'total_amount',
        'paid_amount',
        'outstanding_amount',
        'payment_status',
        'notes',
        'return_notes',
    ];

    protected $casts = [
        'rental_start_date' => 'date',
        'rental_end_date' => 'date',
        'actual_return_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'outstanding_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rentalOrderItems(): HasMany
    {
        return $this->hasMany(RentalOrderItem::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalOrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'active']);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
            ->where('rental_end_date', '<', now()->toDateString());
    }

    public function getDaysRentedAttribute(): int
    {
        return $this->rental_start_date->diffInDays($this->rental_end_date) + 1;
    }

    public function getDaysOverdueAttribute(): int
    {
        if ($this->status !== 'active' || $this->rental_end_date >= now()) {
            return 0;
        }

        return now()->diffInDays($this->rental_end_date);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status === 'active' && $this->rental_end_date < now()->toDateString();
    }

    public function updatePaymentStatus(): void
    {
        $totalPaid = $this->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $this->paid_amount = $totalPaid;
        $this->outstanding_amount = $this->total_amount - $totalPaid;

        if ($totalPaid == 0) {
            $this->payment_status = 'pending';
        } elseif ($totalPaid < $this->total_amount) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'paid';
        }

        $this->save();
    }

    public function markAsReturned(array $returnData = []): void
    {
        $this->actual_return_date = $returnData['return_date'] ?? now()->toDateString();
        $this->status = 'returned';
        $this->return_notes = $returnData['notes'] ?? null;
        
        // Update instrument availability
        foreach ($this->rentalOrderItems as $item) {
            $item->instrument->updateAvailability();
        }

        $this->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->order_number)) {
                $model->order_number = 'RO-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }
}
