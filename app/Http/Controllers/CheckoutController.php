<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\ShippingAddress;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreatedMail;
use App\Mail\NewOrderAdminMail;

class CheckoutController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product.category')->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        $cart = (object) [
            'items' => $cartItems,
            'total' => $total
        ];
        
        $user = auth()->user();

        // Get default address from user profile addresses book
        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        // Get provinces from local DB
        $provinces = \App\Models\Province::orderBy('name')->get();

        return view('checkout.index', compact('cart', 'user', 'defaultAddress', 'provinces'));
    }

    public function cities(Request $request)
    {
        $provinceId = $request->query('province_id');
        if (!$provinceId) {
            return response()->json([]);
        }

        $cities = \App\Models\City::where('province_id', $provinceId)->orderBy('name')->get();
        $mappedCities = $cities->map(function ($city) {
            return [
                'city_id' => $city->id,
                'province_id' => $city->province_id,
                'province' => $city->province->name,
                'type' => $city->type ?? 'Kabupaten',
                'city_name' => $city->name,
                'postal_code' => $city->postal_code ?? ''
            ];
        });

        return response()->json($mappedCities);
    }

    public function shippingCost(Request $request)
    {
        $request->validate([
            'city_id' => 'required|integer',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

        if ($cartItems->count() === 0) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 400);
        }

        // Sum product weights
        $weight = 0;
        $defaultWeight = (int) Setting::getValue('shipping_default_weight', 500);
        if ($defaultWeight <= 0) {
            $defaultWeight = 500;
        }

        foreach ($cartItems as $item) {
            $prodWeight = (int) ($item->product->weight ?? 0);
            if ($prodWeight <= 0) {
                $prodWeight = $defaultWeight;
            }
            $weight += ($prodWeight * $item->quantity);
        }

        if ($weight <= 0) {
            $weight = 500;
        }

        // Find destination city and resolve its RajaOngkir ID
        $destCity = \App\Models\City::find($request->city_id);
        $rates = [];

        if (!$destCity || !$destCity->rajaongkir_id) {
            // Fallback immediately to flat rate if not mapped
            $rates[] = [
                'courier' => 'Flat Rate',
                'service' => 'Flat',
                'cost' => 20000,
                'etd' => 'Akan dikonfirmasi admin',
                'label' => 'Flat Rate — Rp 20.000 (Estimasi ongkir, akan dikonfirmasi admin)'
            ];
            return response()->json([
                'success' => true,
                'rates' => $rates,
                'weight' => $weight
            ]);
        }

        $destination = $destCity->rajaongkir_id;

        // Resolve origin city RajaOngkir ID from settings
        $originCityId = Setting::getValue('shipping_origin_city', '');
        if ($originCityId) {
            $originCity = \App\Models\City::find($originCityId);
        } else {
            $envRajaId = env('STORE_ORIGIN_CITY_ID', 153);
            $originCity = \App\Models\City::where('rajaongkir_id', $envRajaId)->first();
        }
        $origin = $originCity && $originCity->rajaongkir_id ? $originCity->rajaongkir_id : 153;

        // Toggles for active couriers
        $courierToggles = [
            'jne' => (bool) Setting::getValue('shipping_courier_jne', 1),
            'jnt' => (bool) Setting::getValue('shipping_courier_jnt', 1),
            'sicepat' => (bool) Setting::getValue('shipping_courier_sicepat', 1),
        ];

        $apiFailed = false;

        // Fetch JNE costs if JNE is active
        $jneResults = [];
        if ($courierToggles['jne']) {
            $jneResults = $this->rajaOngkir->getCost($origin, $destination, $weight, 'jne');
            if (empty($jneResults)) {
                $apiFailed = true;
            }
        }

        // Populate JNE options
        if (!empty($jneResults)) {
            foreach ($jneResults as $result) {
                if (isset($result['code']) && strtolower($result['code']) === 'jne') {
                    foreach ($result['costs'] as $cost) {
                        $rates[] = [
                            'courier' => 'JNE',
                            'service' => $cost['service'],
                            'cost' => (int) $cost['cost'][0]['value'],
                            'etd' => $cost['cost'][0]['etd'] . ' hari',
                            'label' => 'JNE ' . $cost['service'] . ' — Rp ' . number_format($cost['cost'][0]['value'], 0, ',', '.') . ' (' . $cost['cost'][0]['etd'] . ' hari)'
                        ];
                    }
                }
            }
        }

        // Fetch or derive J&T costs if active
        if ($courierToggles['jnt']) {
            $jntResults = $this->rajaOngkir->getCost($origin, $destination, $weight, 'jnt');
            $jntAdded = false;

            if (!empty($jntResults)) {
                foreach ($jntResults as $result) {
                    if (isset($result['code']) && (strtolower($result['code']) === 'jnt' || strtolower($result['code']) === 'pos')) {
                        foreach ($result['costs'] as $cost) {
                            $rates[] = [
                                'courier' => 'J&T',
                                'service' => $cost['service'],
                                'cost' => (int) $cost['cost'][0]['value'],
                                'etd' => $cost['cost'][0]['etd'] . ' hari',
                                'label' => 'J&T ' . $cost['service'] . ' — Rp ' . number_format($cost['cost'][0]['value'], 0, ',', '.') . ' (' . $cost['cost'][0]['etd'] . ' hari)'
                            ];
                            $jntAdded = true;
                        }
                    }
                }
            }

            // Derive J&T if API fails/unsupported (e.g. Starter version limitations)
            if (!$jntAdded && !empty($rates)) {
                $jneReg = collect($rates)->firstWhere('courier', 'JNE');
                if ($jneReg) {
                    $derivedCost = max(10000, $jneReg['cost'] - 2000);
                    $rates[] = [
                        'courier' => 'J&T',
                        'service' => 'EZ',
                        'cost' => $derivedCost,
                        'etd' => '2-4 hari',
                        'label' => 'J&T EZ — Rp ' . number_format($derivedCost, 0, ',', '.') . ' (2-4 hari)'
                    ];
                }
            }
        }

        // Fetch or derive SiCepat costs if active
        if ($courierToggles['sicepat']) {
            $sicepatResults = $this->rajaOngkir->getCost($origin, $destination, $weight, 'sicepat');
            $sicepatAdded = false;

            if (!empty($sicepatResults)) {
                foreach ($sicepatResults as $result) {
                    if (isset($result['code']) && strtolower($result['code']) === 'sicepat') {
                        foreach ($result['costs'] as $cost) {
                            $rates[] = [
                                'courier' => 'SiCepat',
                                'service' => $cost['service'],
                                'cost' => (int) $cost['cost'][0]['value'],
                                'etd' => $cost['cost'][0]['etd'] . ' hari',
                                'label' => 'SiCepat ' . $cost['service'] . ' — Rp ' . number_format($cost['cost'][0]['value'], 0, ',', '.') . ' (' . $cost['cost'][0]['etd'] . ' hari)'
                            ];
                            $sicepatAdded = true;
                        }
                    }
                }
            }

            // Derive SiCepat if API fails/unsupported
            if (!$sicepatAdded && !empty($rates)) {
                $jneReg = collect($rates)->firstWhere('courier', 'JNE');
                if ($jneReg) {
                    $derivedCost = max(10000, $jneReg['cost'] - 1000);
                    $rates[] = [
                        'courier' => 'SiCepat',
                        'service' => 'REG',
                        'cost' => $derivedCost,
                        'etd' => '2-3 hari',
                        'label' => 'SiCepat REG — Rp ' . number_format($derivedCost, 0, ',', '.') . ' (2-3 hari)'
                    ];
                }
            }
        }

        // Fallback to flat rate Rp 20.000 if all API requests failed or returned empty
        if (empty($rates) || ($apiFailed && count($rates) === 0)) {
            $rates[] = [
                'courier' => 'Flat Rate',
                'service' => 'Flat',
                'cost' => 20000,
                'etd' => 'Akan dikonfirmasi admin',
                'label' => 'Flat Rate — Rp 20.000 (Estimasi ongkir, akan dikonfirmasi admin)'
            ];
        }

        return response()->json([
            'success' => true,
            'rates' => $rates,
            'weight' => $weight
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string',
            'province_id' => 'required|integer',
            'province_name' => 'required|string|max:255',
            'city_id' => 'required|integer',
            'city_name' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'courier' => 'required|string|max:100',
            'courier_service' => 'required|string|max:100',
            'shipping_cost' => 'required|integer',
            'estimated_delivery' => 'nullable|string|max:255',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();

        try {
            // 1. Calculate subtotal & check stock with shared update locks
            $subtotal = 0;
            $lockedProducts = [];
            foreach ($cartItems as $item) {
                $product = \App\Models\Product::lockForUpdate()->find($item->product_id);

                if (!$product || $product->stock < $item->quantity) {
                    throw new \Exception("Stok untuk produk " . ($product->name ?? 'produk') . " tidak mencukupi");
                }
                
                $subtotal += $product->final_price * $item->quantity;
                $lockedProducts[] = [
                    'product' => $product,
                    'quantity' => $item->quantity,
                ];
            }

            // 2. Process voucher backend validation with lockForUpdate
            $voucherId = null;
            $discountAmount = 0;
            $appliedVoucherSession = session()->get('applied_voucher');
            if ($appliedVoucherSession) {
                $voucher = \App\Models\Voucher::where('code', $appliedVoucherSession['code'])->first();
                if ($voucher) {
                    $voucher = \App\Models\Voucher::lockForUpdate()->find($voucher->id);
                    [$isValid, $errorMessage] = $voucher->isValidFor($subtotal);
                    if ($isValid) {
                        $voucherId = $voucher->id;
                        $discountAmount = $voucher->calculateDiscountFor($subtotal);
                        $voucher->increment('used_count');
                    } else {
                        session()->forget('applied_voucher');
                        throw new \Exception("Voucher tidak valid: " . $errorMessage);
                    }
                }
            }

            // Determine shipping cost, taking free shipping threshold setting into account
            $freeShippingMin = (int) Setting::getValue('shipping_free_min_spend', 500000);
            $shippingCost = $subtotal >= $freeShippingMin ? 0 : (int) $request->shipping_cost;
            $totalPrice = max(0, $subtotal - $discountAmount) + $shippingCost;

            // Create Order
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_price' => $totalPrice,
                'recipient_name' => $request->recipient_name,
                'shipping_address' => $request->address_line,
                'city' => $request->city_name,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'courier' => $request->courier,
                'courier_service' => $request->courier_service,
                'shipping_cost' => $shippingCost,
                'estimated_delivery' => $request->estimated_delivery ?: '2-3 hari',
                'voucher_id' => $voucherId,
                'discount_amount' => $discountAmount,
            ]);

            // Save to shipping_addresses table
            $order->shippingAddress()->create([
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'address_line' => $request->address_line,
                'province_id' => $request->province_id,
                'province_name' => $request->province_name,
                'city_id' => $request->city_id,
                'city_name' => $request->city_name,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
            ]);

            // Create order items and decrement stock
            foreach ($lockedProducts as $locked) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $locked['product']->id,
                    'quantity' => $locked['quantity'],
                    'price' => $locked['product']->final_price,
                ]);

                $locked['product']->decrement('stock', $locked['quantity']);
            }

            // If Midtrans, generate snap token
            if ($request->payment_method === 'Midtrans') {
                $midtransService = app(\App\Services\MidtransService::class);
                $snapToken = $midtransService->createSnapToken($order);
                $order->update(['payment_token' => $snapToken]);
            }

            // Clear DB Cart
            Cart::where('user_id', auth()->id())->delete();
            session()->forget('applied_voucher');

            DB::commit();

            // Send automatic emails (silent fail to ensure checkout succeeds even if mailer configuration is empty)
            try {
                Mail::to(auth()->user()->email)->send(new OrderCreatedMail($order));
                Mail::to('admin@samawa.com')->send(new NewOrderAdminMail($order));
            } catch (\Exception $mailEx) {
                logger()->error('Checkout mail error: ' . $mailEx->getMessage());
            }

            return redirect()->route('checkout.success', $order->order_code)->with('success', 'Pesanan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage() ?: 'Terjadi kesalahan saat membuat pesanan');
        }
    }

    public function success($orderCode)
    {
        $order = Order::with('items.product', 'shippingAddress')->where('order_code', $orderCode)->firstOrFail();

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    /**
     * Handle Midtrans Callback Webhook
     */
    public function midtransCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        
        // Log webhook payload
        $logPath = storage_path('logs/midtrans.log');
        file_put_contents(
            $logPath,
            '[' . now() . '] Webhook Received: ' . json_encode($request->all()) . PHP_EOL,
            FILE_APPEND
        );

        // Verify Signature
        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if ($signature !== $request->signature_key) {
            file_put_contents(
                $logPath,
                '[' . now() . '] Signature Verification Failed. Received: ' . $request->signature_key . ', Computed: ' . $signature . PHP_EOL,
                FILE_APPEND
            );
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Midtrans order_id isORD-XXXXXX-timestamp
        $orderCodeParts = explode('-', $orderId);
        $orderCode = $orderCodeParts[0] . '-' . ($orderCodeParts[1] ?? '');

        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;
        $paymentType = $request->payment_type;

        DB::beginTransaction();
        try {
            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $order->update(['payment_status' => 'pending']);
                    } else {
                        $order->update(['payment_status' => 'paid']);
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $order->update(['payment_status' => 'paid']);
            } elseif ($transactionStatus == 'pending') {
                $order->update(['payment_status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->update(['payment_status' => 'failed']);
            }

            DB::commit();
            
            file_put_contents(
                $logPath,
                '[' . now() . '] Order ' . $orderCode . ' updated successfully. Payment status: ' . $order->payment_status . PHP_EOL,
                FILE_APPEND
            );

            return response()->json(['message' => 'Callback processed successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            file_put_contents(
                $logPath,
                '[' . now() . '] Transaction failed for order ' . $orderCode . '. Error: ' . $e->getMessage() . PHP_EOL,
                FILE_APPEND
            );
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
