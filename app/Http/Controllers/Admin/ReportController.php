<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'delivered')
            ->sum('total_price');

        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completedOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'delivered')
            ->count();

        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'user')->count();

        $salesByCategory = OrderItem::selectRaw('categories.name as category_name, SUM(order_items.quantity) as total_sold, SUM(order_items.price * order_items.quantity) as total_revenue')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'delivered')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();

        $salesByMonth = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_price) as total, COUNT(*) as orders')
            ->where('status', 'delivered')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topProducts = OrderItem::selectRaw('products.name as product_name, SUM(order_items.quantity) as total_sold, SUM(order_items.price * order_items.quantity) as total_revenue')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'delivered')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        $recentOrders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
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
