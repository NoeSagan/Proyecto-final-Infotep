<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'passenger_count',
        'total_cost',
        'status',
        'delivery_plate',
        'delivery_mileage',
        'delivery_fuel_level',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'total_cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function extras(): BelongsToMany
    {
        return $this->belongsToMany(Extra::class, 'reservation_extra')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function canBeCancelled(): bool
    {
        if ($this->status === 'pendiente') return true;
        if ($this->status !== 'confirmada') return false;
        return now()->lessThan(Carbon::parse($this->start_date));
    }

    public function cancellationFeePercent(): int
    {
        if ($this->status === 'pendiente') return 0;
        $hours = now()->diffInHours(Carbon::parse($this->start_date), false);
        if ($hours >= 48) return 0;
        if ($hours >= 24) return 25;
        return 50;
    }

    public function cancellationFee(): float
    {
        return round((float) $this->total_cost * $this->cancellationFeePercent() / 100, 2);
    }
}
