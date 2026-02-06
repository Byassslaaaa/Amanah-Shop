<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryMovement;
use App\Models\Credit\InstallmentPayment;
use App\Models\Order;
use App\Models\Product\Product;
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
     * Export inventory report to CSV
     */
    public function exportInventory(Request $request)
    {
        $query = InventoryMovement::with(['product', 'createdBy']);

        // Apply same filters as inventory()
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }
        if ($request->has('product_id') && $request->product_id !== '') {
            $query->where('product_id', $request->product_id);
        }
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->orderBy('created_at', 'desc')->get();

        $filename = 'inventory_report_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($movements) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'Tanggal',
                'Produk',
                'SKU',
                'Tipe',
                'Jumlah',
                'Sumber',
                'Referensi',
                'Catatan',
                'Dibuat Oleh'
            ]);

            // Data rows
            foreach ($movements as $movement) {
                fputcsv($file, [
                    $movement->created_at->format('d/m/Y H:i'),
                    $movement->product->name ?? '-',
                    $movement->product->sku ?? '-',
                    $movement->type === 'in' ? 'Masuk' : 'Keluar',
                    $movement->quantity,
                    $this->getSourceLabel($movement->source),
                    $movement->reference_number ?? '-',
                    $movement->notes ?? '-',
                    $movement->createdBy->name ?? '-'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export payment report to CSV
     */
    public function exportPayments(Request $request)
    {
        $query = InstallmentPayment::with(['order.user']);

        // Apply same filters as payments()
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('paid_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('paid_date', '<=', $request->end_date);
        }
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->orderBy('paid_date', 'desc')->get();

        $filename = 'payment_report_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header row
            fputcsv($file, [
                'No. Order',
                'Pelanggan',
                'Cicilan Ke',
                'Jatuh Tempo',
                'Tanggal Bayar',
                'Jumlah Tagihan',
                'Jumlah Dibayar',
                'Status'
            ]);

            // Data rows
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->order->order_number ?? '-',
                    $payment->order->user->name ?? '-',
                    $payment->installment_number ?? '-',
                    $payment->due_date ? $payment->due_date->format('d/m/Y') : '-',
                    $payment->paid_date ? $payment->paid_date->format('d/m/Y') : '-',
                    number_format($payment->amount_due, 0, ',', '.'),
                    number_format($payment->amount_paid, 0, ',', '.'),
                    $this->getPaymentStatusLabel($payment->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get human-readable source label
     */
    private function getSourceLabel($source)
    {
        $labels = [
            'manual' => 'Manual',
            'purchase' => 'Pembelian',
            'adjustment' => 'Penyesuaian',
            'order' => 'Pesanan',
            'return' => 'Retur',
        ];
        return $labels[$source] ?? ucfirst($source);
    }

    /**
     * Get human-readable payment status label
     */
    private function getPaymentStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'overdue' => 'Terlambat',
            'partial' => 'Sebagian',
        ];
        return $labels[$status] ?? ucfirst($status);
    }
}
