<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'keywords',
        'notes',
        'amazon_listing',
        'shopify_listing',
        'raw_images_path',
        'product_images_path',
    ];

    protected $casts = [
        'keywords' => 'array',
        'amazon_listing' => 'array',
        'shopify_listing' => 'array',
    ];
    /**
     * Get the user that owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all images for this product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get only raw images.
     */
    public function rawImages(): HasMany
    {
        return $this->images()->where('type', 'raw')->orderBy('order');
    }

    /**
     * Get only product images.
     */
    public function productImages(): HasMany
    {
        return $this->images()->where('type', 'product')->orderBy('order');
    }

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'keywords' => 'array',
            'amazon_listing' => 'array',
            'shopify_listing' => 'array',
            'amazon_synced_at' => 'datetime',
            'shopify_synced_at' => 'datetime',
            'exported_at' => 'datetime',
            'price' => 'decimal:2',
        ];
    }
}
