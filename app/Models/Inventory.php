<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $fillable = [
        'variant_id',
        'location_id',
        'qty_on_hand',
        'qty_reserved',
    ];

    protected $casts = [
        'qty_on_hand' => 'integer',
        'qty_reserved' => 'integer',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function getAvailableQtyAttribute(): int
    {
        return max(0, $this->qty_on_hand - $this->qty_reserved);
    }
}