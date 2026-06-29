<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = auth()->user()->wishlist()->with('product.category')->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'message' => 'Silakan login terlebih dahulu',
                'redirect' => route('login')
            ], 401);
        }

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $count = Wishlist::where('user_id', auth()->id())->count();
            return response()->json([
                'message' => 'Produk dihapus dari wishlist',
                'status' => 'removed',
                'count' => $count
            ]);
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
            ]);
            $count = Wishlist::where('user_id', auth()->id())->count();
            return response()->json([
                'message' => 'Produk ditambahkan ke wishlist',
                'status' => 'added',
                'count' => $count
            ]);
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Produk sudah ada di wishlist'], 400);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
        ]);

        $count = Wishlist::where('user_id', auth()->id())->count();
        return response()->json([
            'message' => 'Produk ditambahkan ke wishlist',
            'count' => $count
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
        }

        $count = Wishlist::where('user_id', auth()->id())->count();
        return response()->json([
            'message' => 'Produk dihapus dari wishlist',
            'count' => $count
        ]);
    }

    public function moveToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = auth()->id();
        $productId = $request->product_id;
        $product = Product::findOrFail($productId);

        if ($product->stock < 1) {
            return response()->json(['message' => 'Stok produk tidak mencukupi'], 400);
        }

        // Add to cart
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($product->stock < ($cartItem->quantity + 1)) {
                return response()->json(['message' => 'Stok produk tidak mencukupi di keranjang'], 400);
            }
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        // Remove from wishlist
        Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        $wishlistCount = Wishlist::where('user_id', $userId)->count();
        $cartCount = Cart::where('user_id', $userId)->sum('quantity');

        return response()->json([
            'message' => 'Produk berhasil dipindahkan ke keranjang',
            'wishlist_count' => $wishlistCount,
            'cart_count' => $cartCount
        ]);
    }
}
