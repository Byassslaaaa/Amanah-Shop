<?php

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Controller;
use App\Models\Credit\ManualCreditPayment;
use Illuminate\Http\Request;

class ManualCreditPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = ManualCreditPayment::with('manualCredit');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('due_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('due_date', '<=', $request->end_date);
        }

        // Search by customer
        if ($request->filled('search')) {
            $query->whereHas('manualCredit', function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('credit_number', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $query->orderBy('due_date', 'asc')
            ->paginate(10)
            ->appends($request->query());

        // Summary
        $pendingCount = ManualCreditPayment::where('status', 'pending')->count();
        $paidCount = ManualCreditPayment::where('status', 'paid')->count();
        $overdueCount = ManualCreditPayment::where('status', 'overdue')->count();

        return view('admin.credits.payments.index', compact('payments', 'pendingCount', 'paidCount', 'overdueCount'));
    }

    public function overdueList(Request $request)
    {
        $overduePayments = ManualCreditPayment::with('manualCredit')
            ->where('status', 'overdue')
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('admin.credits.payments.overdue', compact('overduePayments'));
    }

    public function show(ManualCreditPayment $payment)
    {
        $payment->load('manualCredit.installmentPlan');
        return view('admin.credits.payments.show', compact('payment'));
    }

    public function verifyPayment(Request $request, ManualCreditPayment $payment)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        // Validate amount
        $remainingDue = $payment->amount_due - $payment->amount_paid;
        if ($request->amount_paid > $remainingDue) {
            return redirect()->back()
                ->withErrors(['amount_paid' => "Jumlah pembayaran melebihi sisa tagihan (Rp " . number_format($remainingDue, 0, ',', '.') . ")"]);
        }

        // Update payment
        $payment->amount_paid += $request->amount_paid;
        $payment->paid_date = $request->payment_date;
        $payment->notes = $request->notes;
        $payment->verified_by = auth()->id();
        $payment->verified_at = now();

        // Update status
        if ($payment->amount_paid >= $payment->amount_due) {
            $payment->status = 'paid';
        } else {
            $payment->status = 'partial';
        }

        $payment->save();

        // Update credit balance
        $payment->manualCredit->updateBalance();

        return redirect()->route('admin.credits.payments.index')
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    public function report(Request $request)
    {
        // Default to current month
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Summary
        $totalCollected = ManualCreditPayment::whereNotNull('paid_date')
            ->whereBetween('paid_date', [$startDate, $endDate])
            ->sum('amount_paid');

        $totalPending = ManualCreditPayment::where('status', 'pending')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->sum('amount_due');

        $totalOverdue = ManualCreditPayment::where('status', 'overdue')
            ->sum('amount_due');

        // Payment trend - last 6 months
        $paymentTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $collected = ManualCreditPayment::whereNotNull('paid_date')
                ->whereYear('paid_date', $month->year)
                ->whereMonth('paid_date', $month->month)
                ->sum('amount_paid');

            $paymentTrend[] = [
                'month' => $month->format('M Y'),
                'amount' => $collected
            ];
        }

        // Payments list
        $payments = ManualCreditPayment::with('manualCredit')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('admin.credits.payments.report', compact(
            'startDate',
            'endDate',
            'totalCollected',
            'totalPending',
            'totalOverdue',
            'paymentTrend',
            'payments'
        ));
    }
}
