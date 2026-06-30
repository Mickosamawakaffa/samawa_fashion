<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Exception;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create Snap Token for an Order
     *
     * @param \App\Models\Order $order
     * @return string
     * @throws Exception
     */
    public function createSnapToken($order)
    {
        // Midtrans requires a unique order ID for each request. 
        // Using order_code + a timestamp is extremely safe for sandbox testing.
        $midtransOrderId = $order->order_code . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => (int) $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'email' => $order->user->email ?? 'customer@samawa-fashion.com',
                'phone' => $order->phone,
                'billing_address' => [
                    'first_name' => $order->recipient_name,
                    'phone' => $order->phone,
                    'address' => $order->shipping_address,
                ],
                'shipping_address' => [
                    'first_name' => $order->recipient_name,
                    'phone' => $order->phone,
                    'address' => $order->shipping_address,
                ]
            ]
        ];

        // Add item details
        $itemDetails = [];
        $order->loadMissing('items.product');

        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => (int) $item->quantity,
                'name' => substr($item->product->name, 0, 50),
            ];
        }

        // Add shipping cost as a separate item line if positive
        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Ongkos Kirim (' . strtoupper($order->courier) . ')',
            ];
        }

        $params['item_details'] = $itemDetails;

        try {
            return Snap::getSnapToken($params);
        } catch (Exception $e) {
            logger()->error('Midtrans Snap Token Generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
