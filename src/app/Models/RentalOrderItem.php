<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_order_id',
        'instrument_id',
        'quantity',
        'unit_price',
        'rental_days',
        'total_price',
        'condition_out',
        'condition_in',
        'damage_notes',
        'damage_fee',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'damage_fee' => 'decimal:2',
    ];

    public function rentalOrder(): BelongsTo
    {
        return $this->belongsTo(RentalOrder::class);
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function calculateTotalPrice(): void
    {
        $this->total_price = $this->unit_price * $this->quantity;
        $this->save();
    }

    public function assessDamage(string $conditionIn, ?string $notes = null, float $fee = 0): void
    {
        $this->condition_in = $conditionIn;
        $this->damage_notes = $notes;
        $this->damage_fee = $fee;
        $this->save();

        // Update rental order total if damage fee is applied
        if ($fee > 0) {
            $this->rentalOrder->total_amount += $fee;
            $this->rentalOrder->outstanding_amount += $fee;
            $this->rentalOrder->save();
        }
    }
}
