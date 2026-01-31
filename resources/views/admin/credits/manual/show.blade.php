@extends('layouts.admin')

@section('title', 'Manual Credit Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manual Credit Details</h1>
            <p class="text-gray-600 mt-1">View manual credit information and payment history</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.credits.manual.edit', $credit->id) }}" 
                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Edit Credit
            </a>
            <a href="{{ route('admin.credits.manual.index') }}" 
                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <\!-- Credit Information -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Credit Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Customer Name</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $credit->customer_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Phone</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $credit->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Credit Amount</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">Rp {{ number_format($credit->amount, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Paid Amount</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">Rp {{ number_format($credit->paid_amount ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Remaining Amount</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">Rp {{ number_format($credit->amount - ($credit->paid_amount ?? 0), 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Due Date</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $credit->due_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full mt-1 
                            {{ $credit->status == 'paid' ? 'bg-green-100 text-green-800' : ($credit->status == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($credit->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created At</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $credit->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @if($credit->notes)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-sm text-gray-600">Notes</p>
                    <p class="text-sm text-gray-900 mt-1">{{ $credit->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <\!-- Payment Summary -->
        <div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Credit</span>
                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($credit->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Paid</span>
                        <span class="text-sm font-medium text-green-600">Rp {{ number_format($credit->paid_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="pt-3 border-t border-gray-100 flex justify-between">
                        <span class="text-sm font-medium text-gray-900">Remaining</span>
                        <span class="text-sm font-bold text-red-600">Rp {{ number_format($credit->amount - ($credit->paid_amount ?? 0), 0, ',', '.') }}</span>
                    </div>
                </div>
                @if($credit->status \!= 'paid')
                <button class="w-full mt-4 px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Record Payment
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
