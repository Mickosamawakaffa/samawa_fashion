<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'status',
        'total_price',
        'recipient_name',
        'shipping_address',
        'city',
        'postal_code',
        'phone',
        'payment_method',
        'payment_status',
        'courier',
        'courier_service',
        'shipping_cost',
        'tracking_number',
        'estimated_delivery',
        'shipped_at',
        'delivered_at',
        'completed_at',
        'processing_at',
        'payment_token',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'processing_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_code = 'ORD-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }
}
