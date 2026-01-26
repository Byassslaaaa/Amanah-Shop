<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Village;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Check if admin village has set shipping origin location
        $needsShippingSetup = false;
        if ($user->isAdmin() && $user->village_id) {
            $village = Village::find($user->village_id);
            $needsShippingSetup = !$village || empty($village->origin_city_id);
        }

        // Basic Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalVillages = Village::where('status', 'active')->count();
        $totalCategories = Category::count();

        // Admin hanya lihat produk desanya, SuperAdmin lihat semua
        $productsQuery = Product::query();
        if ($user->isAdmin() && $user->village_id) {
            $productsQuery->where('village_id', $user->village_id);
        }

        $totalProducts = $productsQuery->count();
        $activeProducts = (clone $productsQuery)->where('status', 'active')->count();
        $inactiveProducts = (clone $productsQuery)->where('status', 'inactive')->count();
        $lowStockProducts = (clone $productsQuery)->where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = (clone $productsQuery)->where('stock', 0)->count();

        // Recent Products
        $recentProducts = (clone $productsQuery)
            ->with(['category', 'village'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top Villages by Product Count (SuperAdmin only)
        $topVillages = null;
        if ($user->isSuperAdmin()) {
            $topVillages = Village::withCount('products')
                ->where('status', 'active')
                ->orderBy('products_count', 'desc')
                ->take(5)
                ->get();
        }

        // Category Statistics
        $categoryStats = Category::withCount(['products' => function($query) use ($user) {
            if ($user->isAdmin() && $user->village_id) {
                $query->where('village_id', $user->village_id);
            }
        }])
        ->orderBy('products_count', 'desc')
        ->take(6)
        ->get();

        // Orders Statistics (if user's village or all for superadmin)
        $ordersQuery = Order::query();
        if ($user->isAdmin() && $user->village_id) {
            $ordersQuery->whereHas('items', function($query) use ($user) {
                $query->where('village_id', $user->village_id);
            });
        }

        $totalOrders = $ordersQuery->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'pending')->count();
        $completedOrders = (clone $ordersQuery)->where('status', 'completed')->count();

        // Revenue (if needed)
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
            'totalVillages',
            'totalProducts',
            'totalCategories',
            'activeProducts',
            'inactiveProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'recentProducts',
            'topVillages',
            'categoryStats',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'productsByType',
            'needsShippingSetup'
        ));
    }
}
