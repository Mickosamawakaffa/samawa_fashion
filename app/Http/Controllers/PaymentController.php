<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function upload($orderId)
    {
        $order = Order::with('payment')->findOrFail($orderId);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment) {
            return redirect()->back()->with('error', 'Bukti pembayaran sudah diupload');
        }

        return view('payment.upload', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        $order = Order::findOrFail($orderId);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment) {
            return redirect()->back()->with('error', 'Bukti pembayaran sudah diupload');
        }

        $proofImagePath = $request->file('proof_image')->store('payments', 'public');

        Payment::create([
            'order_id' => $order->id,
            'amount' => $order->total_price,
            'payment_method' => $order->payment_method,
            'proof_image' => $proofImagePath,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        return redirect()->route('payment.success', $orderId)->with('success', 'Bukti pembayaran berhasil diupload');
    }

    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payment.success', compact('order'));
    }
}
