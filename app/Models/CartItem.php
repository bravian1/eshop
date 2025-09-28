<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'qty',
        'price_cents_at_add',
        'chosen_axis_values',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price_cents_at_add' => 'integer',
        'chosen_axis_values' => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
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
        return $this->price_cents_at_add * $this->qty;
    }

    public function getSubtotalAttribute(): float
    {
        return $this->subtotal_cents / 100;
    }
}