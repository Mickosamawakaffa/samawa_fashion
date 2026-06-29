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
        ]);

        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'shipped_at' => $request->status === 'shipped' ? now() : $order->shipped_at,
            'completed_at' => $request->status === 'delivered' ? now() : $order->completed_at,
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
}
