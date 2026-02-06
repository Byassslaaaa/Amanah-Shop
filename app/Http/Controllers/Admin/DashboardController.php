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
        // Optimize inventory queries - single query
        $inventoryStats = Product::selectRaw('
            COUNT(CASE WHEN stock < 10 AND stock > 0 THEN 1 END) as low_stock,
            COUNT(CASE WHEN stock = 0 THEN 1 END) as out_of_stock,
            COUNT(CASE WHEN status = "active" THEN 1 END) as active_count
        ')->first();

        $lowStockProducts = $inventoryStats->low_stock ?? 0;
        $outOfStockProducts = $inventoryStats->out_of_stock ?? 0;
        $activeProducts = $inventoryStats->active_count ?? 0;

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
        // Optimize financial queries - single query for income and expense
        $financialStats = FinancialTransaction::whereMonth('transaction_date', $thisMonth->month)
            ->whereYear('transaction_date', $thisMonth->year)
            ->selectRaw('
                SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expense
            ')
            ->first();

        $monthlyIncome = $financialStats->total_income ?? 0;
        $monthlyExpense = $financialStats->total_expense ?? 0;
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
        // Optimize credit queries
        $creditStats = ManualCredit::selectRaw('
            COUNT(CASE WHEN status = "active" THEN 1 END) as active_count,
            SUM(CASE WHEN status = "active" THEN remaining_balance ELSE 0 END) as total_receivable,
            SUM(IFNULL(total_paid, 0)) as total_paid
        ')->first();

        $activeCredits = $creditStats->active_count ?? 0;
        $totalReceivable = $creditStats->total_receivable ?? 0;
        $totalCreditPaid = $creditStats->total_paid ?? 0;

        // Overdue payments - single query
        $overdueStats = ManualCreditPayment::where('status', '!=', 'paid')
            ->where('due_date', '<', $today)
            ->selectRaw('
                COUNT(*) as count,
                SUM(amount_due - amount_paid) as total_amount
            ')
            ->first();

        $overduePayments = $overdueStats->count ?? 0;
        $overdueAmount = $overdueStats->total_amount ?? 0;

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
