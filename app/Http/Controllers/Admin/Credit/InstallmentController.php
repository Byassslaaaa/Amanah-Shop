<?php

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Controller;
use App\Models\Credit\InstallmentPayment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentController extends Controller
{
    /**
     * Display a listing of all installment payments
     */
    public function index(Request $request)
    {
        $query = InstallmentPayment::with(['order.user']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by overdue
        if ($request->has('overdue') && $request->overdue === '1') {
            $query->overdue();
        }

        // Search by order number or customer name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $installments = $query->orderBy('due_date', 'asc')->paginate(20);

        return view('admin.credit.installments.index', compact('installments'));
    }

    /**
     * Display installments for a specific order
     */
    public function show(Order $order)
    {
        if (!$order->isCreditOrder()) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Order ini bukan order kredit');
        }

        $order->load([
            'user',
            'shippingAddress',
            'items.product',
            'installmentPlan',
            'installmentPayments' => function ($query) {
                $query->orderBy('installment_number');
            }
        ]);

        return view('admin.credit.installments.show', compact('order'));
    }

    /**
     * Show form to verify installment payment
     */
    public function verifyForm(InstallmentPayment $installment)
    {
        $installment->load('order.user');

        return view('admin.credit.installments.verify', compact('installment'));
    }

    /**
     * Verify and process installment payment
     */
    public function verify(Request $request, InstallmentPayment $installment)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'paid_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $installment->markAsPaid(
                $validated['amount_paid'],
                $validated['paid_date'],
                $validated['notes'],
                auth()->id()
            );

            DB::commit();

            return redirect()->route('admin.credit.installments.show', $installment->order)
                ->with('success', 'Pembayaran cicilan berhasil diverifikasi');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display overdue installments
     */
    public function overdue()
    {
        $overdueInstallments = InstallmentPayment::overdue()
            ->with(['order.user'])
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        return view('admin.credit.installments.overdue', compact('overdueInstallments'));
    }

    /**
     * Get installment statistics
     */
    public function statistics()
    {
        $stats = [
            'total_credit_orders' => Order::credit()->count(),
            'active_installments' => Order::credit()
                ->where('payment_status', 'installment_active')
                ->count(),
            'total_outstanding' => Order::credit()
                ->sum('remaining_balance'),
            'overdue_count' => InstallmentPayment::overdue()->count(),
            'overdue_amount' => InstallmentPayment::overdue()
                ->sum(DB::raw('amount_due - amount_paid')),
            'paid_this_month' => InstallmentPayment::where('status', 'paid')
                ->whereMonth('paid_date', now()->month)
                ->whereYear('paid_date', now()->year)
                ->sum('amount_paid'),
        ];

        return view('admin.credit.installments.statistics', compact('stats'));
    }
}
