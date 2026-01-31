@extends('layouts.admin')

@section('title', 'Edit Manual Credit')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Manual Credit</h1>
        <p class="text-gray-600 mt-1">Update manual credit details</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.credits.manual.update', $credit->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="customer_name" id="customer_name" 
                        value="{{ old('customer_name', $credit->customer_name) }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter customer name" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="text" name="phone" id="phone" 
                        value="{{ old('phone', $credit->phone) }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter phone number">
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Credit Amount <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                        <input type="number" name="amount" id="amount" 
                            value="{{ old('amount', $credit->amount) }}" step="0.01" min="0"
                            class="w-full text-sm border border-gray-200 rounded-lg pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="0.00" required>
                    </div>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="due_date" id="due_date" 
                        value="{{ old('due_date', $credit->due_date->format('Y-m-d')) }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" 
                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required>
                        <option value="unpaid" {{ old('status', $credit->status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="partial" {{ old('status', $credit->status) == 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="paid" {{ old('status', $credit->status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Additional notes...">{{ old('notes', $credit->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.credits.manual.index') }}" 
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Update Credit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
