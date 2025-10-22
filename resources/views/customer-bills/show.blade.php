@extends('layouts.professional')

@section('title', 'Customer Bill Details')

@section('header')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('customer-bills.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Customer Bill #{{ $customerBill->id }}</h1>
                <p class="mt-2 text-sm text-gray-600">Bill details for {{ $customerBill->customer->name }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('customer-bills.print', $customerBill) }}" 
               target="_blank"
               style="background-color: #7c3aed !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-flex !important; align-items: center !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
               onmouseover="this.style.backgroundColor='#6d28d9' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
               onmouseout="this.style.backgroundColor='#7c3aed' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print
            </a>
            <a href="{{ route('customer-bills.pdf', $customerBill) }}" 
               style="background-color: #ea580c !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-flex !important; align-items: center !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
               onmouseover="this.style.backgroundColor='#dc2626' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
               onmouseout="this.style.backgroundColor='#ea580c' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download PDF
            </a>
            <a href="{{ route('customer-bills.edit', $customerBill) }}" 
               style="background-color: #059669 !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-flex !important; align-items: center !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
               onmouseover="this.style.backgroundColor='#047857' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
               onmouseout="this.style.backgroundColor='#059669' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Bill
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Bill Information -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Customer Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Name:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $customerBill->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Phone:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $customerBill->customer->phone }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Type:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ ucfirst($customerBill->customer->customer_type) }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Address:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $customerBill->customer->address }}</span>
                    </div>
                </div>
            </div>

            <!-- Bill Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500">Bill Number:</span>
                        <span class="ml-2 text-sm text-gray-900">#{{ $customerBill->id }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Bill Date:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $customerBill->bill_date->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Total Amount:</span>
                        <span class="ml-2 text-lg font-bold text-green-600">₹{{ number_format($customerBill->total_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-500">Items Count:</span>
                        <span class="ml-2 text-sm text-gray-900">{{ $customerBill->items->count() }} items</span>
                    </div>
                </div>
            </div>
        </div>

        @if($customerBill->notes)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
                <p class="text-sm text-gray-700">{{ $customerBill->notes }}</p>
            </div>
        @endif
    </div>

    <!-- Bill Items -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Bill Items</h3>
        </div>
        
        @if($customerBill->items->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Merchant</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Rate (₹)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customerBill->items as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->product->unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->merchantBillItem && $item->merchantBillItem->merchantBill)
                                        <div class="text-sm text-gray-900">{{ $item->merchantBillItem->merchantBill->merchant->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $item->merchantBillItem->merchantBill->bill_date->format('M d, Y') }}</div>
                                    @else
                                        <div class="text-sm text-gray-500">Manual Entry</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">{{ number_format($item->net_quantity, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">₹{{ number_format($item->rate, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-gray-900">₹{{ number_format($item->total_amount, 2) }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Grand Total:</td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-green-600">₹{{ number_format($customerBill->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <p class="text-gray-500">No items found in this bill.</p>
            </div>
        @endif
    </div>
</div>
@endsection
