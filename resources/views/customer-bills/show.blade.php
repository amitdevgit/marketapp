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
                <table id="billItemsTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    Product
                                </div>
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Merchant
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    Quantity
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Rate (₹)
                                </div>
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                <div class="flex items-center justify-end">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Total (₹)
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customerBill->items as $item)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 border-b border-gray-100">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-xs text-gray-500 bg-gray-100 rounded-full px-3 py-1 inline-block mt-1">
                                        {{ $item->product->unit }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @if($item->merchantBillItem && $item->merchantBillItem->merchantBill)
                                        <div class="text-sm font-medium text-gray-900">{{ $item->merchantBillItem->merchantBill->merchant->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->merchantBillItem->merchantBill->bill_date->format('M d, Y') }}</div>
                                    @else
                                        <div class="text-sm text-gray-500 italic">Manual Entry</div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($item->net_quantity, 2) }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">₹{{ number_format($item->rate, 2) }}</div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="text-sm font-bold text-gray-900">₹{{ number_format($item->total_amount, 2) }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gradient-to-r from-green-50 to-emerald-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-lg font-bold text-gray-900">Grand Total:</td>
                            <td class="px-6 py-4 text-right text-xl font-bold text-green-600">₹{{ number_format($customerBill->total_amount, 2) }}</td>
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

<!-- DataTables Scripts -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<style>
/* Custom DataTables Styling for Items Table */
#billItemsTable_wrapper .dataTables_length,
#billItemsTable_wrapper .dataTables_filter,
#billItemsTable_wrapper .dataTables_info,
#billItemsTable_wrapper .dataTables_paginate {
    padding: 12px 20px;
    color: #374151;
    font-weight: 500;
}

#billItemsTable_wrapper .dataTables_filter input {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 14px;
    transition: all 0.2s;
}

#billItemsTable_wrapper .dataTables_filter input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    outline: none;
}

#billItemsTable_wrapper .dataTables_paginate .paginate_button {
    border-radius: 8px;
    margin: 0 2px;
    padding: 8px 12px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    transition: all 0.2s;
}

#billItemsTable_wrapper .dataTables_paginate .paginate_button:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
}

#billItemsTable_wrapper .dataTables_paginate .paginate_button.current {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
    box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

#billItemsTable tbody tr:hover {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    transform: scale(1.01);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>

<script>
$(document).ready(function() {
    $('#billItemsTable').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        responsive: true,
        language: {
            search: "Search items:",
            lengthMenu: "Show _MENU_ items per page",
            info: "Showing _START_ to _END_ of _TOTAL_ items",
            emptyTable: '<div class="text-center py-12"><svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg><p class="text-sm text-gray-500">No items found in this bill.</p></div>',
            zeroRecords: '<div class="text-center py-12"><svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg><p class="text-sm text-gray-500">No items found matching your search.</p></div>'
        }
    });
});
</script>
@endsection
