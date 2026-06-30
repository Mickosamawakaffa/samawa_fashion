<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdatedMail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderByDesc('created_at')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,refunded',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // Validasi: tidak bisa ubah status ke "shipped" jika nomor resi masih kosong
        if ($request->status === 'shipped' && !$request->tracking_number) {
            return redirect()->back()
                ->withErrors(['tracking_number' => 'Nomor resi wajib diisi jika status diubah ke Shipped'])
                ->withInput();
        }

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'tracking_number' => $request->tracking_number,
            'processing_at' => $request->status === 'processing' ? ($order->processing_at ?: now()) : $order->processing_at,
            'shipped_at' => $request->status === 'shipped' ? ($order->shipped_at ?: now()) : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? ($order->delivered_at ?: now()) : $order->delivered_at,
            'completed_at' => $request->status === 'delivered' ? ($order->completed_at ?: now()) : $order->completed_at,
        ]);

        // Send status update email notification (silent fail)
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
        } catch (\Exception $mailEx) {
            logger()->error('Order status update mail error: ' . $mailEx->getMessage());
        }

        return redirect()->route('admin.orders.index')->with('success', 'Status pesanan berhasil diperbarui');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus');
    }

    public function printInvoice($id)
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        return $pdf->download('invoice-' . $order->order_code . '.pdf');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        if ($request->status === 'shipped' && empty($request->tracking_number) && empty($order->tracking_number)) {
            return back()->withErrors(['tracking_number' => 'Nomor resi wajib diisi sebelum mengubah status menjadi Dikirim']);
        }

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number ?? $order->tracking_number,
            'payment_status' => $request->payment_status === 'unpaid' ? 'pending' : 'paid',
            'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        if ($request->status === 'shipped') {
            try {
                Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
            } catch (\Exception $mailEx) {
                logger()->error('Order shipping notification mail error: ' . $mailEx->getMessage());
            }
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui');
    }
}
