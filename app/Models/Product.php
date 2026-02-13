<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id','name','slug','sku','condition','brand',
        'short_description','description','price','compare_at_price',
        'stock_qty','is_active','sort_order',
        'badge_text','rating','rating_count','delivery_text',
        'discount_percent','meta',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'meta' => 'array',
        'price' => 'float',
        'compare_at_price' => 'float',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        // IMPORTANT: if primaryImage is NOT eager loaded,
        // this can cause N+1 queries. So in controller, always ->with('primaryImage')
        $path = $this->primaryImage?->image_path;

        if (!$path) return asset('images/placeholder-product.png');

        // if stored in storage/app/public/...
        if (!str_starts_with($path, 'http') && !str_starts_with($path, 'images/')) {
            return asset('storage/' . ltrim($path, '/'));
        }

        // if stored in public/images/...
        return asset(ltrim($path, '/'));
    }
}
