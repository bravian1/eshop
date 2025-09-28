<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Variant extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'product_id',
        'sku',
        'price_cents',
        'cost_cents',
        'weight_g',
        'width_mm',
        'height_mm',
        'depth_mm',
        'is_active',
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'cost_cents' => 'integer',
        'weight_g' => 'integer',
        'width_mm' => 'integer',
        'height_mm' => 'integer',
        'depth_mm' => 'integer',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function axisValues(): BelongsToMany
    {
        return $this->belongsToMany(VariantAxisValue::class, 'variant_composition');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function stockReservations(): HasMany
    {
        return $this->hasMany(StockReservation::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(300)
            ->height(300)
            ->sharpen(10);
    }

    public function getPriceAttribute(): float
    {
        return $this->price_cents / 100;
    }

    public function getCostAttribute(): ?float
    {
        return $this->cost_cents ? $this->cost_cents / 100 : null;
    }
}