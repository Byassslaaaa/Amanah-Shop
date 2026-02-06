<?php

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Controller;
use App\Models\Credit\ManualCredit;
use App\Models\Credit\ManualCreditPayment;
use Illuminate\Http\Request;

class ManualCreditController extends Controller
{
    public function index(Request $request)
    {
        $query = ManualCredit::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('credit_number', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        $credits = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // Summary
        $activeCredits = ManualCredit::active()->count();
        $totalOutstanding = ManualCredit::active()->sum('remaining_balance');
        $overdueAmount = ManualCreditPayment::whereHas('manualCredit', function($q) {
            $q->where('status', 'active');
        })->where('status', 'overdue')->sum('amount_due');

        return view('admin.credits.manual.index', compact('credits', 'activeCredits', 'totalOutstanding', 'overdueAmount'));
    }

    public function create()
    {
        return view('admin.credits.manual.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'description' => 'required|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'installment_months' => 'required|integer|min:1|max:360',
            'loan_amount' => 'required|numeric|min:1',
            'down_payment' => 'nullable|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today'
        ]);

        $downPayment = $request->down_payment ?? 0;
        $loanAmount = $request->loan_amount;

        // Validate down payment
        if ($downPayment >= $loanAmount) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['down_payment' => 'Uang muka harus lebih kecil dari jumlah pinjaman.']);
        }

        // Calculate loan details
        $principalAmount = $loanAmount - $downPayment;

        // Interest calculation: (principal * rate / 100) * (months / 12)
        // This gives simple interest based on the duration
        $interestAmount = ($principalAmount * $request->interest_rate / 100) * ($request->installment_months / 12);

        $totalAmount = $principalAmount + $interestAmount;
        $monthlyInstallment = $totalAmount / $request->installment_months;

        // Create credit
        $credit = ManualCredit::create([
            'credit_number' => ManualCredit::generateCreditNumber(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'description' => $request->description,
            'interest_rate' => $request->interest_rate,
            'loan_amount' => $loanAmount,
            'down_payment' => $downPayment,
            'principal_amount' => $principalAmount,
            'interest_amount' => $interestAmount,
            'total_amount' => $totalAmount,
            'monthly_installment' => $monthlyInstallment,
            'installment_months' => $request->installment_months,
            'total_paid' => 0,
            'remaining_balance' => $totalAmount,
            'status' => 'active',
            'start_date' => $request->start_date,
            'created_by' => auth()->id()
        ]);

        // Generate payment schedule
        $startDate = \Carbon\Carbon::parse($request->start_date);
        for ($i = 1; $i <= $request->installment_months; $i++) {
            $dueDate = $startDate->copy()->addMonths($i);

            ManualCreditPayment::create([
                'manual_credit_id' => $credit->id,
                'payment_number' => ManualCreditPayment::generatePaymentNumber(),
                'installment_number' => $i,
                'amount_due' => $monthlyInstallment,
                'amount_paid' => 0,
                'due_date' => $dueDate,
                'status' => 'pending'
            ]);
        }

        return redirect()->route('admin.credits.manual.show', $credit)
            ->with('success', 'Kredit manual berhasil dibuat.');
    }

    public function show(ManualCredit $credit)
    {
        $credit->load(['payments' => function($q) {
            $q->orderBy('installment_number');
        }, 'creator']);

        return view('admin.credits.manual.show', compact('credit'));
    }

    public function edit(ManualCredit $credit)
    {
        // Only allow editing if no payments have been made
        if ($credit->total_paid > 0) {
            return redirect()->route('admin.credits.manual.show', $credit)
                ->with('error', 'Kredit tidak dapat diedit karena sudah ada pembayaran.');
        }

        return view('admin.credits.manual.edit', compact('credit'));
    }

    public function update(Request $request, ManualCredit $credit)
    {
        // Only allow update if no payments have been made
        if ($credit->total_paid > 0) {
            return redirect()->route('admin.credits.manual.show', $credit)
                ->with('error', 'Kredit tidak dapat diupdate karena sudah ada pembayaran.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'description' => 'required|string'
        ]);

        $credit->update([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'description' => $request->description
        ]);

        return redirect()->route('admin.credits.manual.show', $credit)
            ->with('success', 'Kredit manual berhasil diupdate.');
    }

    public function destroy(ManualCredit $credit)
    {
        // Only allow deletion if no payments have been made
        if ($credit->total_paid > 0) {
            return redirect()->route('admin.credits.manual.index')
                ->with('error', 'Kredit tidak dapat dihapus karena sudah ada pembayaran.');
        }

        $credit->payments()->delete();
        $credit->delete();

        return redirect()->route('admin.credits.manual.index')
            ->with('success', 'Kredit manual berhasil dihapus.');
    }

    public function recordPaymentForm(ManualCredit $credit)
    {
        $credit->load('payments');
        $pendingPayments = $credit->payments()->where('status', 'pending')->orderBy('installment_number')->get();

        return view('admin.credits.manual.record-payment', compact('credit', 'pendingPayments'));
    }

    public function storePayment(Request $request, ManualCredit $credit)
    {
        $request->validate([
            'payment_id' => 'required|exists:manual_credit_payments,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        $payment = ManualCreditPayment::findOrFail($request->payment_id);

        // Validate payment belongs to credit
        if ($payment->manual_credit_id != $credit->id) {
            return redirect()->back()->with('error', 'Pembayaran tidak valid.');
        }

        // Validate amount
        $remainingDue = $payment->amount_due - $payment->amount_paid;
        if ($request->amount_paid > $remainingDue) {
            return redirect()->back()
                ->withInput()
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
        $credit->updateBalance();

        return redirect()->route('admin.credits.manual.show', $credit)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }
}
