<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreatedMail;
use App\Mail\NewOrderAdminMail;

class CheckoutController extends Controller
{
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

        return view('checkout.index', compact('cart', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->subtotal;
        });

        // Free shipping if total > 500.000, otherwise flat 15.000
        $shippingCost = $subtotal > 500000 ? 0 : 15000;
        $totalPrice = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_price' => $totalPrice,
                'recipient_name' => $request->recipient_name,
                'shipping_address' => $request->shipping_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                if ($item->product->stock < $item->quantity) {
                    throw new \Exception("Stok untuk produk " . $item->product->name . " tidak mencukupi");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->final_price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Clear DB Cart
            Cart::where('user_id', auth()->id())->delete();

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
        $order = Order::with('items.product')->where('order_code', $orderCode)->firstOrFail();

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
