<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'guest_email',
        'guest_phone',
        'shipping_address_id',
        'billing_address_id',
        'delivery_option_id',
        'delivery_zone_id',
        'location_id',
        'delivery_price_cents',
        'delivery_et_hours_min',
        'delivery_et_hours_max',
        'status',
        'payment_intent_id',
        'refund_status',
        'refund_amount_cents',
    ];

    protected $casts = [
        'delivery_price_cents' => 'integer',
        'delivery_et_hours_min' => 'integer',
        'delivery_et_hours_max' => 'integer',
        'refund_amount_cents' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getSubtotalCentsAttribute(): int
    {
        return $this->items->sum(fn($item) => $item->unit_price_cents * $item->qty);
    }

    public function getTotalCentsAttribute(): int
    {
        return $this->subtotal_cents + $this->delivery_price_cents;
    }

    public function getTotalAttribute(): float
    {
        return $this->total_cents / 100;
    }
}