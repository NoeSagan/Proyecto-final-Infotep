<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Vehicle extends Model
{
    protected $fillable = [
        'category_id',
        'brand',
        'model',
        'model_alternative',
        'plate',
        'price_per_day',
        'status',
        'transmission_type',
        'fuel_type',
        'passenger_capacity',
        'luggage_capacity',
        'key_features',
        'maintenance_notes',
        'available_from',
        'current_mileage',
        'current_fuel_level',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }
}
