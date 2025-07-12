<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $totalOrders = Order::count();
        $totalCustomers = User::count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        
        // Revenue stats
        $totalRevenue = Order::where('status', 'completed')->sum('total');
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total');
        
        // Recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Best selling products
        $bestSellers = Product::bestSellers(5)->get();
        
        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        // Monthly sales chart data (last 6 months)
        $monthlySales = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('month', 'year')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalCustomers', 
            'totalProducts',
            'totalCategories',
            'totalRevenue',
            'monthlyRevenue',
            'recentOrders',
            'bestSellers',
            'ordersByStatus',
            'monthlySales'
        ));
    }
} 