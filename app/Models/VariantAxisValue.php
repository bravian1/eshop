<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VariantAxisValue extends Model
{
    protected $fillable = [
        'variant_axis_id',
        'value',
        'meta',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'meta' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function variantAxis(): BelongsTo
    {
        return $this->belongsTo(VariantAxis::class);
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class, 'variant_composition');
    }
}