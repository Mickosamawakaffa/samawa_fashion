<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = auth()->user()->orders()->orderByDesc('created_at');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        $orders = $query->paginate(10);
        return view('orders.index', compact('orders', 'status'));
    }

    public function show($order_code)
    {
        $order = Order::where('user_id', auth()->id())
            ->where('order_code', $order_code)
            ->with('items.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }

    public function cancelOrder($order_code)
    {
        DB::beginTransaction();
        try {
            $order = Order::where('user_id', auth()->id())
                ->where('order_code', $order_code)
                ->firstOrFail();

            if ($order->status !== 'pending') {
                return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
            }

            // Restore stocks
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            $order->update([
                'status' => 'cancelled'
            ]);

            DB::commit();
            
            // Fire status updated email (silent fail)
            try {
                \Illuminate\Support\Facades\Mail::to(auth()->user()->email)->send(new \App\Mail\OrderStatusUpdatedMail($order));
            } catch (\Exception $mailEx) {
                logger()->error('Order cancelled mail error: ' . $mailEx->getMessage());
            }

            return redirect()->back()->with('success', 'Pesanan Anda berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal membatalkan pesanan.');
        }
    }

    public function confirmReceived($order_code)
    {
        DB::beginTransaction();
        try {
            $order = Order::where('user_id', auth()->id())
                ->where('order_code', $order_code)
                ->firstOrFail();

            if ($order->status !== 'shipped') {
                return redirect()->back()->with('error', 'Status pesanan tidak sesuai.');
            }

            $order->update([
                'status' => 'delivered'
            ]);

            DB::commit();

            // Fire status updated email (silent fail)
            try {
                \Illuminate\Support\Facades\Mail::to(auth()->user()->email)->send(new \App\Mail\OrderStatusUpdatedMail($order));
            } catch (\Exception $mailEx) {
                logger()->error('Order delivered mail error: ' . $mailEx->getMessage());
            }

            return redirect()->back()->with('success', 'Pesanan telah selesai dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengkonfirmasi penerimaan pesanan.');
        }
    }
}
