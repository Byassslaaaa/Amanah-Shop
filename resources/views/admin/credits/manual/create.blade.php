@extends('layouts.admin')

@section('title', 'Create Manual Credit')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Create Manual Credit</h1>
        <p class="text-gray-600 mt-1">Add a new manual credit record</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <form action="{{ route('admin.credits.manual.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Customer Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="customer_name" id="customer_name" 
                        value="{{ old('customer_name') }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Enter customer name" required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="text" name="phone" id="phone" 
                        value="{{ old('phone') }}"
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
                            value="{{ old('amount') }}" step="0.01" min="0"
                            class="w-full text-sm border border-gray-200 rounded-lg pl-12 pr-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="0.00" required>
                    </div>
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Due Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="due_date" id="due_date" 
                        value="{{ old('due_date') }}"
                        class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        required>
                </div>
            </div>

            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea name="notes" id="notes" rows="4"
                    class="w-full text-sm border border-gray-200 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    placeholder="Additional notes...">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.credits.manual.index') }}" 
                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Create Credit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
