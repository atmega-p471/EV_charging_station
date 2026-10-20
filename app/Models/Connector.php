<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Connector extends Model
{
    protected $fillable = ['charger_id','type','power_kw','price_per_kwh'];

    public function charger(): BelongsTo
    {
        return $this->belongsTo(Charger::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}