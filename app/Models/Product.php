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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'sizes' => 'array',
        'colors' => 'array',
        'is_active' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
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

    public function getFinalPriceAttribute()
    {
        return $this->price - ($this->price * ($this->discount / 100));
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
