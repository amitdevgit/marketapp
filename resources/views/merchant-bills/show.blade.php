@extends('layouts.professional')

@section('title', 'Merchant Bill Details')

<style>
/* Ensure header buttons are properly styled */
.header-edit-btn {
    background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%) !important;
    color: white !important;
    border: none !important;
}

.header-edit-btn:hover {
    background: linear-gradient(135deg, #d97706 0%, #dc2626 100%) !important;
    color: white !important;
}

.header-generate-btn {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
    color: white !important;
    border: none !important;
}

.header-generate-btn:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    color: white !important;
}

/* Ensure all buttons in this page have proper styling */
a[href*="edit"], button[type="submit"] {
    color: white !important;
    text-decoration: none !important;
}
</style>

@section('header')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('merchant-bills.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bill #{{ $merchantBill->id }}</h1>
                <p class="mt-2 text-sm text-gray-600">Merchant bill details and items.</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('merchant-bills.edit', $merchantBill) }}" class="header-edit-btn inline-flex items-center px-4 py-2 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('merchant-bills.generate-customer-bills', $merchantBill) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="header-generate-btn inline-flex items-center px-4 py-2 font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Generate Customer Bills
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- Bill Items - Full Width Only -->
<div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="h-12 w-12 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-lg flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-semibold text-gray-900">Bill Items</h3>
                <p class="text-sm text-gray-600">Products and quantities</p>
            </div>
        </div>
        <div class="text-right">
            <div class="text-sm text-gray-500 mb-1">Merchant</div>
            <div class="text-lg font-semibold text-gray-900">{{ $merchantBill->merchant->name }}</div>
            <div class="text-sm text-gray-500 mt-2 mb-1">Billing Date</div>
            <div class="text-lg font-semibold text-gray-900">{{ $merchantBill->bill_date->format('M d, Y') }}</div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Misc Adj.</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($merchantBill->items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->customer->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->product->name }}</div>
                        <div class="text-sm text-gray-500">{{ $item->product->unit }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->weight }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $item->misc_adjustment }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">₹{{ number_format($item->rate, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $item->net_quantity }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">₹{{ number_format($item->total_amount, 2) }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


