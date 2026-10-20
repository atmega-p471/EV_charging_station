<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Charger extends Model
{
    protected $fillable = ['station_id','name'];

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function connectors(): HasMany
    {
        return $this->hasMany(Connector::class);
    }
}