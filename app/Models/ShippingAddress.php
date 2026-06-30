<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';

    protected $fillable = [
        'order_id',
        'recipient_name',
        'phone',
        'address_line',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district',
        'postal_code',
    ];

    /**
     * Get the order that owns the shipping address.
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
