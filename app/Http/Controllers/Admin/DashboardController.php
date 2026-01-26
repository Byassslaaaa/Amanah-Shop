<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Basic Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalCategories = Category::count();

        // Amanah Shop: Single shop - all admins see all products
        $productsQuery = Product::query();

        $totalProducts = $productsQuery->count();
        $activeProducts = (clone $productsQuery)->where('status', 'active')->count();
        $inactiveProducts = (clone $productsQuery)->where('status', 'inactive')->count();
        $lowStockProducts = (clone $productsQuery)->where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = (clone $productsQuery)->where('stock', 0)->count();

        // Recent Products
        $recentProducts = (clone $productsQuery)
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Category Statistics
        $categoryStats = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();

        // Orders Statistics
        $ordersQuery = Order::query();

        $totalOrders = $ordersQuery->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'pending')->count();
        $completedOrders = (clone $ordersQuery)->where('status', 'completed')->count();

        // Revenue
        $totalRevenue = (clone $ordersQuery)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Product Type Distribution
        $productsByType = [
            'barang' => (clone $productsQuery)->where('type', 'barang')->count(),
            'jasa' => (clone $productsQuery)->where('type', 'jasa')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalCategories',
            'activeProducts',
            'inactiveProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'recentProducts',
            'categoryStats',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'productsByType'
        ));
    }
}
