<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'type', 'file_path', 'file_name', 'file_size', 'mime_type', 'order', 'gdrive_file_id'])]
class ProductImage extends Model
{
    /**
     * Get the product that owns the image.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the full URL to the image.
     */
    public function getUrlAttribute(): string
    {
        return url('storage/' . $this->file_path);
    }
}
