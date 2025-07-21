<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstrumentCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function instruments(): HasMany
    {
        return $this->hasMany(Instrument::class, 'category_id');
    }

    public function availableInstruments(): HasMany
    {
        return $this->hasMany(Instrument::class, 'category_id')
            ->where('is_available', true)
            ->where('is_active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
