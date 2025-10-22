@extends('layouts.professional')

@section('title', 'Merchant Bill Details')

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
            <a href="{{ route('merchant-bills.edit', $merchantBill) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-lg hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            <form action="{{ route('merchant-bills.generate-customer-bills', $merchantBill) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-lg hover:shadow-xl">
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
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Bill Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-xl font-bold text-white">{{ substr($merchantBill->merchant->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Bill Information</h3>
                    <p class="text-sm text-gray-600">Merchant and bill details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Merchant Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $merchantBill->merchant->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Business Name</label>
                    <p class="text-lg text-gray-900">{{ $merchantBill->merchant->business_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Bill Date</label>
                    <p class="text-lg text-gray-900">{{ $merchantBill->bill_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    @if($merchantBill->status === 'paid')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Paid
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Bill Items -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
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
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Misc Adj.</th>
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
                                <div class="text-sm text-gray-900">₹{{ number_format($item->rate, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->misc_adjustment }}</div>
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

        <!-- Notes -->
        @if($merchantBill->notes)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Notes</h3>
                    <p class="text-sm text-gray-600">Additional information</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-900 whitespace-pre-wrap">{{ $merchantBill->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('merchant-bills.edit', $merchantBill) }}" class="w-full flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                    <svg class="h-5 w-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Edit Bill</span>
                </a>
                
                <form action="{{ route('merchant-bills.generate-customer-bills', $merchantBill) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200">
                        <svg class="h-5 w-5 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="text-sm font-medium text-green-800">Generate Customer Bills</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Bill Summary -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill Summary</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Items</span>
                    <span class="text-sm font-medium text-gray-900">{{ $merchantBill->items->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Bill Date</span>
                    <span class="text-sm font-medium text-gray-900">{{ $merchantBill->bill_date->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    @if($merchantBill->status === 'paid')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Paid
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </div>
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                        <span class="text-xl font-bold text-green-600">₹{{ number_format($merchantBill->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
