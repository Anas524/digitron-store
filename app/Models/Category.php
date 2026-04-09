<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
        'image_path',
        'icon',
        'show_in_menu',
        'show_on_home',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_in_menu' => 'boolean',
        'show_on_home' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        if (!str_starts_with($this->image_path, 'http') && !str_starts_with($this->image_path, 'images/')) {
            return asset('storage/' . ltrim($this->image_path, '/'));
        }

        return asset(ltrim($this->image_path, '/'));
    }
}
