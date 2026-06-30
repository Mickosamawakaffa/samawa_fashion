<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $cartItems = Cart::where('user_id', auth()->id())->with('product.category')->get();
            $total = $cartItems->sum(function ($item) {
                return $item->subtotal;
            });
            $cartCount = $cartItems->sum('quantity');
        } else {
            $sessionCart = session()->get('cart', []);
            $cartItems = collect();
            $total = 0;
            $cartCount = 0;
            
            foreach ($sessionCart as $productId => $quantity) {
                $product = Product::with('category')->find($productId);
                if ($product) {
                    $subtotal = $product->final_price * $quantity;
                    $total += $subtotal;
                    $cartCount += $quantity;
                    
                    // Create dummy item object resembling DB model structure
                    $cartItems->push((object) [
                        'id' => $productId, // use product id as cart item id for session logic
                        'product_id' => $productId,
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $subtotal
                    ]);
                }
            }
        }

        $cart = (object) [
            'items' => $cartItems,
            'total' => $total,
            'count' => $cartCount
        ];
        
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return response()->json(['message' => 'Stok tidak mencukupi'], 400);
        }

        if (auth()->check()) {
            // DB Cart
            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                if ($product->stock < ($cartItem->quantity + $quantity)) {
                    return response()->json(['message' => 'Stok tidak mencukupi'], 400);
                }
                $cartItem->increment('quantity', $quantity);
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
            
            $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
            return response()->json(['message' => 'Produk ditambahkan ke keranjang', 'cart_count' => $cartCount]);
        } else {
            // Session Cart for Guest
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                if ($product->stock < ($cart[$productId] + $quantity)) {
                    return response()->json(['message' => 'Stok tidak mencukupi'], 400);
                }
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }

            session()->put('cart', $cart);
            
            $cartCount = array_sum($cart);
            return response()->json(['message' => 'Produk ditambahkan ke keranjang (Session)', 'cart_count' => $cartCount]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $quantity = $request->quantity;
        
        if (auth()->check()) {
            $cartItem = Cart::findOrFail($request->cart_item_id);
            if ($cartItem->user_id !== auth()->id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            if ($cartItem->product->stock < $quantity) {
                return response()->json(['message' => 'Stok tidak mencukupi'], 400);
            }
            $cartItem->update(['quantity' => $quantity]);
            
            $cartItems = Cart::where('user_id', auth()->id())->get();
            $total = $cartItems->sum(function ($item) {
                return $item->subtotal;
            });
            $cartCount = $cartItems->sum('quantity');
            $subtotalVal = $cartItem->subtotal;
        } else {
            $productId = $request->cart_item_id;
            $product = Product::findOrFail($productId);
            if ($product->stock < $quantity) {
                return response()->json(['message' => 'Stok tidak mencukupi'], 400);
            }
            
            $cart = session()->get('cart', []);
            $cart[$productId] = $quantity;
            session()->put('cart', $cart);
            
            $total = 0;
            $cartCount = 0;
            foreach ($cart as $pid => $qty) {
                $p = Product::find($pid);
                if ($p) {
                    $total += $p->final_price * $qty;
                    $cartCount += $qty;
                }
            }
            $subtotalVal = $product->final_price * $quantity;
        }

        return response()->json([
            'message' => 'Keranjang diperbarui',
            'subtotal' => 'Rp ' . number_format($subtotalVal, 0, ',', '.'),
            'total' => 'Rp ' . number_format($total, 0, ',', '.'),
            'cart_count' => $cartCount
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required',
        ]);
        
        if (auth()->check()) {
            $cartItem = Cart::findOrFail($request->cart_item_id);
            if ($cartItem->user_id !== auth()->id()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            $cartItem->delete();
            
            $cartItems = Cart::where('user_id', auth()->id())->get();
            $total = $cartItems->sum(function ($item) {
                return $item->subtotal;
            });
            $cartCount = $cartItems->sum('quantity');
        } else {
            $productId = $request->cart_item_id;
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
            
            $total = 0;
            $cartCount = 0;
            foreach ($cart as $pid => $qty) {
                $p = Product::find($pid);
                if ($p) {
                    $total += $p->final_price * $qty;
                    $cartCount += $qty;
                }
            }
        }

        return response()->json([
            'message' => 'Produk dihapus dari keranjang',
            'total' => 'Rp ' . number_format($total, 0, ',', '.'),
            'cart_count' => $cartCount
        ]);
    }

    /**
     * Apply Voucher to Cart via AJAX
     */
    public function applyVoucher(Request $request)
    {
        if ($request->code === 'CLEAR_VOUCHER') {
            session()->forget('applied_voucher');
            return response()->json([
                'success' => true,
                'message' => 'Voucher dihapus',
                'discount_amount' => 0,
                'discount_formatted' => 'Rp 0',
            ]);
        }

        $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $code = trim($request->code);
        $voucher = \App\Models\Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json(['success' => false, 'message' => 'Kode voucher tidak ditemukan.'], 404);
        }

        // Calculate subtotal
        if (auth()->check()) {
            $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
            $subtotal = $cartItems->sum(function ($item) {
                return $item->product->final_price * $item->quantity;
            });
        } else {
            $sessionCart = session()->get('cart', []);
            $subtotal = 0;
            foreach ($sessionCart as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product) {
                    $subtotal += $product->final_price * $quantity;
                }
            }
        }

        if ($subtotal <= 0) {
            return response()->json(['success' => false, 'message' => 'Keranjang Anda kosong.'], 400);
        }

        // Validate voucher
        [$isValid, $errorMessage] = $voucher->isValidFor($subtotal);
        if (!$isValid) {
            return response()->json(['success' => false, 'message' => $errorMessage], 400);
        }

        $discount = $voucher->calculateDiscountFor($subtotal);
        $newTotal = max(0, $subtotal - $discount);

        // Cache applied voucher in session
        session()->put('applied_voucher', [
            'id' => $voucher->id,
            'code' => $voucher->code,
            'discount' => $discount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Voucher "' . $voucher->code . '" berhasil digunakan.',
            'discount_amount' => $discount,
            'discount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'new_total_formatted' => 'Rp ' . number_format($newTotal, 0, ',', '.'),
            'voucher_code' => $voucher->code,
        ]);
    }
}
