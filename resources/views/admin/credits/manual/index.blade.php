@extends('layouts.admin')

@section('title', 'Manual Credit Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manual Credit Management</h1>
            <p class="text-gray-600 mt-1">Manage manual credit records outside regular orders</p>
        </div>
        <a href="{{ route('admin.credits.manual.create') }}" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
            Add Manual Credit
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($credits as $credit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $credit->customer_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($credit->amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $credit->status == 'paid' ? 'bg-green-100 text-green-800' : ($credit->status == 'partial' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($credit->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $credit->due_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('admin.credits.manual.show', $credit->id) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                            <a href="{{ route('admin.credits.manual.edit', $credit->id) }}" class="text-indigo-600 hover:text-indigo-800 mr-3">Edit</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No manual credits found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
