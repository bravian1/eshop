<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value_cents',
        'percentage',
        'minimum_amount_cents',
        'maximum_discount_cents',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'value_cents' => 'integer',
        'percentage' => 'integer',
        'minimum_amount_cents' => 'integer',
        'maximum_discount_cents' => 'integer',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            });
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('used_count < usage_limit');
            });
    }

    public function calculateDiscount(int $subtotalCents): int
    {
        if ($this->minimum_amount_cents && $subtotalCents < $this->minimum_amount_cents) {
            return 0;
        }

        $discount = match ($this->type) {
            'percentage' => (int) ($subtotalCents * $this->percentage / 100),
            'fixed_amount' => $this->value_cents,
            'free_shipping' => 0, // Handled separately
            default => 0,
        };

        if ($this->maximum_discount_cents) {
            $discount = min($discount, $this->maximum_discount_cents);
        }

        return $discount;
    }
}