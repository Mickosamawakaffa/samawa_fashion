<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'description',
        'price',
        'discount',
        'stock',
        'weight',
        'sizes',
        'colors',
        'image',
        'is_active',
        'is_best_seller',
        'is_new_arrival',
        'is_featured',
        'is_dummy',
        'flash_sale_price',
        'flash_sale_start',
        'flash_sale_end',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'sizes' => 'array',
        'colors' => 'array',
        'weight' => 'integer',
        'is_active' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_featured' => 'boolean',
        'is_dummy' => 'boolean',
        'flash_sale_start' => 'datetime',
        'flash_sale_end' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the primary image from product_images relation.
     * Falls back to the legacy `image` column on products table.
     */
    public function primaryImage()
    {
        $primary = $this->images()->where('is_primary', true)->first();
        if ($primary) {
            return $primary->image_path;
        }
        // Fallback: first image in gallery
        $first = $this->images()->first();
        if ($first) {
            return $first->image_path;
        }
        // Final fallback: legacy single image column
        return $this->image;
    }

    /**
     * Scope to filter only dummy products.
     */
    public function scopeDummy($query)
    {
        return $query->where('is_dummy', true);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->is_flash_sale_active) {
            return (float) $this->flash_sale_price;
        }
        return (float) ($this->price - ($this->price * ($this->discount / 100)));
    }

    public function getIsFlashSaleActiveAttribute()
    {
        if ($this->flash_sale_price === null) {
            return false;
        }
        $now = now();
        return $this->flash_sale_start !== null 
            && $this->flash_sale_end !== null 
            && $now->gte($this->flash_sale_start) 
            && $now->lte($this->flash_sale_end);
    }

    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute()
    {
        return $this->reviews()->count();
    }
}
