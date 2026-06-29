<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'name',
        'role',
        'rating',
        'message',
        'avatar',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
