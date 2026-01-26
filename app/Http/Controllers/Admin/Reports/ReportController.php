<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryMovement;
use App\Models\Credit\InstallmentPayment;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display inventory movement report
     */
    public function inventory(Request $request)
    {
        $query = InventoryMovement::with(['product', 'createdBy']);

        // Filter by type (in/out)
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by product
        if ($request->has('product_id') && $request->product_id !== '') {
            $query->where('product_id', $request->product_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get summary statistics
        $summary = [
            'total_in' => InventoryMovement::where('type', 'in')
                ->when($request->start_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->sum('quantity'),
            'total_out' => InventoryMovement::where('type', 'out')
                ->when($request->start_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->sum('quantity'),
        ];

        $products = Product::orderBy('name')->get();

        return view('admin.reports.inventory', compact('movements', 'summary', 'products'));
    }

    /**
     * Display installment payment report
     */
    public function payments(Request $request)
    {
        $query = InstallmentPayment::with(['order.user']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('paid_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('paid_date', '<=', $request->end_date);
        }

        // Search by customer name or order number
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->orderBy('paid_date', 'desc')->paginate(50);

        // Calculate summary
        $summary = [
            'total_paid' => InstallmentPayment::where('status', 'paid')
                ->when($request->start_date, function ($q) use ($request) {
                    $q->whereDate('paid_date', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($q) use ($request) {
                    $q->whereDate('paid_date', '<=', $request->end_date);
                })
                ->sum('amount_paid'),
            'total_pending' => InstallmentPayment::where('status', 'pending')
                ->sum('amount_due'),
            'total_overdue' => InstallmentPayment::overdue()
                ->sum(DB::raw('amount_due - amount_paid')),
        ];

        return view('admin.reports.payments', compact('payments', 'summary'));
    }

    /**
     * Display credit order summary report
     */
    public function creditOrders(Request $request)
    {
        $query = Order::credit()->with(['user', 'installmentPlan']);

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(30);

        // Summary statistics
        $summary = [
            'total_credit_orders' => Order::credit()
                ->when($request->start_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->count(),
            'total_credit_amount' => Order::credit()
                ->when($request->start_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->sum('total_credit_amount'),
            'total_down_payment' => Order::credit()
                ->when($request->start_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->start_date);
                })
                ->when($request->end_date, function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->end_date);
                })
                ->sum('down_payment_amount'),
            'total_outstanding' => Order::credit()
                ->whereIn('payment_status', ['installment_active', 'installment_overdue'])
                ->sum('remaining_balance'),
        ];

        return view('admin.reports.credit-orders', compact('orders', 'summary'));
    }

    /**
     * Export inventory report (placeholder for future CSV/Excel export)
     */
    public function exportInventory(Request $request)
    {
        // TODO: Implement CSV/Excel export
        return redirect()->back()
            ->with('info', 'Fitur export akan segera tersedia');
    }

    /**
     * Export payment report (placeholder for future CSV/Excel export)
     */
    public function exportPayments(Request $request)
    {
        // TODO: Implement CSV/Excel export
        return redirect()->back()
            ->with('info', 'Fitur export akan segera tersedia');
    }
}
