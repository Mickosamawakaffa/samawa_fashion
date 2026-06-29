<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');

        $monthlySales = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total_amount) as total')
            ->where('status', 'completed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bestSellingProducts = Order::selectRaw('product_id, COUNT(*) as count')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->groupBy('product_id')
            ->orderByDesc('count')
            ->limit(5)
            ->with('product')
            ->get();

        $recentOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'monthlySales',
            'bestSellingProducts',
            'recentOrders'
        ));
    }
}
