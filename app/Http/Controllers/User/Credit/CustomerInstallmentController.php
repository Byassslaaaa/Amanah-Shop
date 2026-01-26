<?php

namespace App\Http\Controllers\User\Credit;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Credit\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerInstallmentController extends Controller
{
    /**
     * Display customer's credit orders
     */
    public function index()
    {
        $creditOrders = Order::where('user_id', auth()->id())
            ->credit()
            ->with(['installmentPlan'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.credit.index', compact('creditOrders'));
    }

    /**
     * Display installment details for a specific order
     */
    public function show(Order $order)
    {
        // Ensure order belongs to logged-in user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke order ini');
        }

        if (!$order->isCreditOrder()) {
            return redirect()->route('user.orders.show', $order)
                ->with('error', 'Order ini bukan order kredit');
        }

        $order->load([
            'shippingAddress',
            'items.product',
            'installmentPlan',
            'installmentPayments' => function ($query) {
                $query->orderBy('installment_number');
            }
        ]);

        return view('user.credit.show', compact('order'));
    }

    /**
     * Show form to upload payment proof
     */
    public function paymentProofForm(InstallmentPayment $installment)
    {
        // Ensure installment belongs to logged-in user's order
        if ($installment->order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke cicilan ini');
        }

        return view('user.credit.payment-proof', compact('installment'));
    }

    /**
     * Upload payment proof for an installment
     */
    public function uploadPaymentProof(Request $request, InstallmentPayment $installment)
    {
        // Ensure installment belongs to logged-in user's order
        if ($installment->order->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke cicilan ini');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // Upload payment proof
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Delete old payment proof if exists
            if ($installment->payment_proof) {
                Storage::disk('public')->delete($installment->payment_proof);
            }

            // Update installment
            $installment->update([
                'payment_proof' => $path,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending', // Mark as pending verification
            ]);

            return redirect()->route('user.credit.show', $installment->order)
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupload bukti pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Display payment history
     */
    public function paymentHistory()
    {
        $payments = InstallmentPayment::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->where('status', 'paid')
        ->with(['order'])
        ->orderBy('paid_date', 'desc')
        ->paginate(20);

        return view('user.credit.payment-history', compact('payments'));
    }

    /**
     * Display upcoming payments
     */
    public function upcomingPayments()
    {
        $upcomingPayments = InstallmentPayment::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->whereIn('status', ['pending', 'partial'])
        ->orderBy('due_date', 'asc')
        ->with(['order'])
        ->get();

        return view('user.credit.upcoming', compact('upcomingPayments'));
    }

    /**
     * Display overdue payments for the user
     */
    public function overduePayments()
    {
        $overduePayments = InstallmentPayment::whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->overdue()
        ->orderBy('due_date', 'asc')
        ->with(['order'])
        ->get();

        return view('user.credit.overdue', compact('overduePayments'));
    }
}
