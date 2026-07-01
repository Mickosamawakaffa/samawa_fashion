<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate   = $request->end_date   ?? now()->endOfMonth()->format('Y-m-d');

        // Use explicit time boundaries so orders at any hour of the day are included
        $start = $startDate . ' 00:00:00';
        $end   = $endDate   . ' 23:59:59';

        // ── Revenue & order counts ────────────────────────────────────────────
        // Count ALL non-cancelled orders (includes Processing, Shipped, etc.)
        $totalOrders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->count();

        // Sum revenue from all non-cancelled orders
        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('total_price');

        // Only orders fully delivered
        $completedOrders = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'delivered')
            ->count();

        $totalProducts  = Product::count();
        $totalCustomers = User::where('role', 'user')->count();

        // ── Sales by category ─────────────────────────────────────────────────
        $salesByCategory = OrderItem::selectRaw(
                'categories.name as category_name,
                 SUM(order_items.quantity) as total_sold,
                 SUM(order_items.price * order_items.quantity) as total_revenue'
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        // ── Monthly sales chart (whole year) ──────────────────────────────────
        $salesByMonth = Order::selectRaw(
                'DATE_FORMAT(created_at, "%Y-%m") as month,
                 SUM(total_price) as total,
                 COUNT(*) as orders'
            )
            ->where('status', '!=', 'cancelled')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ── Top-selling products ──────────────────────────────────────────────
        $topProducts = OrderItem::selectRaw(
                'products.name as product_name,
                 SUM(order_items.quantity) as total_sold,
                 SUM(order_items.price * order_items.quantity) as total_revenue'
            )
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // ── Recent orders (all statuses) ──────────────────────────────────────
        $recentOrders = Order::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'totalProducts',
            'totalCustomers',
            'salesByCategory',
            'salesByMonth',
            'topProducts',
            'recentOrders'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $orders = Order::with('user', 'items.product')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $filename = 'sales_report_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Code', 'Customer', 'Date', 'Total', 'Status', 'Payment Method']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->user->name,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->total_price,
                    $order->status,
                    $order->payment_method,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
