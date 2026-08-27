<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Extra extends Model
{
    protected $fillable = ['name', 'price', 'selection_type'];

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class, 'reservation_extra')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
