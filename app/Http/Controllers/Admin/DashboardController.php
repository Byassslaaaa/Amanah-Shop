<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product\Product;
use App\Models\Product\Category;
use App\Models\Order;
use App\Models\Finance\FinancialTransaction;
use App\Models\Inventory\InventoryMovement;
use App\Models\Credit\ManualCredit;
use App\Models\Credit\ManualCreditPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // ========== BASIC STATISTICS ==========
        $totalUsers = User::where('role', 'user')->count();
        $totalCategories = Category::count();
        $totalProducts = Product::count();

        // ========== ORDER STATISTICS ==========
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        // Recent Orders
        $recentOrders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ========== INVENTORY STATISTICS ==========
        $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStockProducts = Product::where('stock', 0)->count();
        $activeProducts = Product::where('status', 'active')->count();

        // Low stock product list
        $lowStockList = Product::with('category')
            ->where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();

        // Recent inventory movements
        $recentMovements = InventoryMovement::with(['product', 'creator'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ========== FINANCIAL STATISTICS ==========
        // This month income/expense
        $monthlyIncome = FinancialTransaction::where('type', 'income')
            ->whereMonth('transaction_date', $thisMonth->month)
            ->whereYear('transaction_date', $thisMonth->year)
            ->sum('amount');

        $monthlyExpense = FinancialTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', $thisMonth->month)
            ->whereYear('transaction_date', $thisMonth->year)
            ->sum('amount');

        $monthlyBalance = $monthlyIncome - $monthlyExpense;

        // Recent financial transactions
        $recentTransactions = FinancialTransaction::with('category')
            ->orderBy('transaction_date', 'desc')
            ->take(5)
            ->get();

        // Income trend (last 6 months)
        $financeTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $income = FinancialTransaction::where('type', 'income')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->sum('amount');
            $expense = FinancialTransaction::where('type', 'expense')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->sum('amount');
            $financeTrend[] = [
                'month' => $month->format('M'),
                'income' => $income,
                'expense' => $expense
            ];
        }

        // ========== CREDIT STATISTICS ==========
        $activeCredits = ManualCredit::where('status', 'active')->count();
        $totalReceivable = ManualCredit::where('status', 'active')->sum('remaining_balance');
        $totalCreditPaid = ManualCredit::sum('amount_paid');

        // Overdue payments
        $overduePayments = ManualCreditPayment::where('status', '!=', 'paid')
            ->where('due_date', '<', $today)
            ->count();

        $overdueAmount = ManualCreditPayment::where('status', '!=', 'paid')
            ->where('due_date', '<', $today)
            ->selectRaw('SUM(amount_due - amount_paid) as total')
            ->value('total') ?? 0;

        // Upcoming payments (next 7 days)
        $upcomingPayments = ManualCreditPayment::with('manualCredit')
            ->where('status', '!=', 'paid')
            ->whereBetween('due_date', [$today, $today->copy()->addDays(7)])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();

        // Recent credits
        $recentCredits = ManualCredit::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // ========== TOP PRODUCTS ==========
        $topProducts = Product::withCount(['orderItems as total_sold' => function($query) {
                $query->selectRaw('COALESCE(sum(quantity), 0)');
            }])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            // Basic
            'totalUsers',
            'totalProducts',
            'totalCategories',
            // Orders
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue',
            'recentOrders',
            // Inventory
            'activeProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'lowStockList',
            'recentMovements',
            // Finance
            'monthlyIncome',
            'monthlyExpense',
            'monthlyBalance',
            'recentTransactions',
            'financeTrend',
            // Credits
            'activeCredits',
            'totalReceivable',
            'totalCreditPaid',
            'overduePayments',
            'overdueAmount',
            'upcomingPayments',
            'recentCredits',
            // Products
            'topProducts'
        ));
    }
}
