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
    public function index(Request $request)
    {
        $status = $request->query('status');
        $query = Order::with('user');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->orderByDesc('created_at')->paginate(10);

        // Counts for filter tabs
        $countPending = Order::where('status', 'pending')->count();
        $countProcessing = Order::where('status', 'processing')->count();
        $countShipped = Order::where('status', 'shipped')->count();
        $countDelivered = Order::where('status', 'delivered')->count();
        $countCancelled = Order::where('status', 'cancelled')->count();

        return view('admin.orders.index', compact(
            'orders', 
            'countPending', 
            'countProcessing', 
            'countShipped', 
            'countDelivered',
            'countCancelled'
        ));
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

    public function quickUpdateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,delivered,cancelled'
        ]);

        $status = $request->status;
        $updateData = ['status' => $status];
        if ($status === 'processing') {
            $updateData['processing_at'] = $order->processing_at ?: now();
        } elseif ($status === 'delivered') {
            $updateData['delivered_at'] = $order->delivered_at ?: now();
            $updateData['completed_at'] = $order->completed_at ?: now();
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);

        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
        } catch (\Exception $mailEx) {
            logger()->error('Order status quick update mail error: ' . $mailEx->getMessage());
        }

        return response()->json(['success' => true]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:processing,delivered,cancelled',
        ]);

        $status = $request->status;
        $orders = Order::whereIn('id', $request->order_ids)->get();

        foreach ($orders as $order) {
            $updateData = ['status' => $status];
            if ($status === 'processing') {
                $updateData['processing_at'] = $order->processing_at ?: now();
            } elseif ($status === 'delivered') {
                $updateData['delivered_at'] = $order->delivered_at ?: now();
                $updateData['completed_at'] = $order->completed_at ?: now();
                $updateData['payment_status'] = 'paid';
            }

            $order->update($updateData);

            try {
                Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order));
            } catch (\Exception $mailEx) {
                logger()->error('Order status bulk update mail error: ' . $mailEx->getMessage());
            }
        }

        return response()->json(['success' => true, 'count' => $orders->count()]);
    }
}
