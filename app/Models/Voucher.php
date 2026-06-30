<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_purchase',
        'max_discount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Check if the voucher is valid for a subtotal
     *
     * @param int $subtotal
     * @param int|null $userId (optional user validation)
     * @return array [bool $isValid, string $errorMessage]
     */
    public function isValidFor($subtotal)
    {
        if (!$this->is_active) {
            return [false, 'Voucher ini tidak aktif'];
        }

        $today = now()->startOfDay();
        if ($this->valid_from->startOfDay()->gt($today)) {
            return [false, 'Voucher belum dapat digunakan'];
        }

        if ($this->valid_until->endOfDay()->lt($today)) {
            return [false, 'Voucher sudah kadaluwarsa'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return [false, 'Batas kuota penggunaan voucher telah habis'];
        }

        if ($subtotal < $this->min_purchase) {
            return [false, 'Minimal belanja untuk menggunakan voucher ini adalah Rp ' . number_format($this->min_purchase, 0, ',', '.')];
        }

        return [true, ''];
    }

    /**
     * Calculate the discount amount for a subtotal
     *
     * @param int $subtotal
     * @return int
     */
    public function calculateDiscountFor($subtotal)
    {
        if ($this->type === 'fixed') {
            return min($subtotal, $this->value);
        }

        // Percentage discount
        $discount = (int) (($subtotal * $this->value) / 100);

        // Limit to max discount if set
        if ($this->max_discount !== null) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }
}
