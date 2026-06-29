<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalOrdersToday = Order::whereDate('created_at', Carbon::today())->count();
        
        // Total revenue this month (paid status)
        $totalRevenueMonth = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $totalUsers = User::count();

        // Query sales log for last 30 days
        $salesRaw = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Build continuous 30-day datasets for Chart.js
        $salesLabels = [];
        $salesValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('Y-m-d');
            $salesLabels[] = Carbon::today()->subDays($i)->format('d M');
            
            $daySale = $salesRaw->firstWhere('date', $date);
            $salesValues[] = $daySale ? (float) $daySale->total : 0;
        }

        // Top 5 recent orders
        $recentOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Top 5 low stock products (< 5)
        $lowStockProducts = Product::where('stock', '<', 5)
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrdersToday',
            'totalRevenueMonth',
            'totalUsers',
            'salesLabels',
            'salesValues',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
