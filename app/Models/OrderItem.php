<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'qty',
        'unit_price_cents',
        'unit_cost_cents',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price_cents' => 'integer',
        'unit_cost_cents' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function getSubtotalCentsAttribute(): int
    {
        return $this->unit_price_cents * $this->qty;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->subtotal_cents / 100;
    }
}