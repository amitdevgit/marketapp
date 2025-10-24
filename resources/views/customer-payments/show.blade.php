@extends('layouts.professional')

@section('title', 'Payment Details #' . $customerPayment->id)

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('customer-payments.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Payment Details #{{ $customerPayment->id }}</h1>
            <p class="mt-2 text-sm text-gray-600">View payment information and details.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <!-- Payment Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center">
                <div class="h-12 w-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Payment #{{ $customerPayment->id }}</h2>
                    <p class="text-sm text-gray-600">Recorded on {{ $customerPayment->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold text-green-600">₹{{ number_format($customerPayment->amount, 2) }}</div>
                @if($customerPayment->status === 'completed')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Completed
                    </span>
                @elseif($customerPayment->status === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        Cancelled
                    </span>
                @endif
            </div>
        </div>

        <!-- Payment Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Customer Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-medium text-indigo-600">
                                {{ strtoupper(substr($customerPayment->customer->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">{{ $customerPayment->customer->name }}</div>
                            <div class="text-sm text-gray-500">{{ $customerPayment->customer->phone }}</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        <strong>Address:</strong> {{ $customerPayment->customer->address }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <strong>Type:</strong> {{ ucfirst($customerPayment->customer->customer_type) }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <strong>Current Balance:</strong> ₹{{ number_format($customerPayment->customer->balance, 2) }}
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Payment Method:</span>
                        <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $customerPayment->payment_method)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Payment Date:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $customerPayment->payment_date->format('M d, Y') }}</span>
                    </div>
                    @if($customerPayment->reference_number)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Reference Number:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $customerPayment->reference_number }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Status:</span>
                        @if($customerPayment->status === 'completed')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completed
                            </span>
                        @elseif($customerPayment->status === 'pending')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Cancelled
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Linked Bill Information -->
        @if($customerPayment->customerBill)
            <div class="mt-8 bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Linked Bill Information</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-medium text-gray-900">
                            <a href="{{ route('customer-bills.show', $customerPayment->customerBill) }}" class="text-blue-600 hover:text-blue-900">
                                Bill #{{ $customerPayment->customerBill->id }}
                            </a>
                        </div>
                        <div class="text-sm text-gray-600">
                            Bill Date: {{ $customerPayment->customerBill->bill_date->format('M d, Y') }}
                        </div>
                        <div class="text-sm text-gray-600">
                            Total Amount: ₹{{ number_format($customerPayment->customerBill->total_amount, 2) }}
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Payment Status:</div>
                        @if($customerPayment->customerBill->payment_status === 'paid')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Paid
                            </span>
                        @elseif($customerPayment->customerBill->payment_status === 'partial')
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Partial
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Pending
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Notes -->
        @if($customerPayment->notes)
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                <p class="text-gray-700">{{ $customerPayment->notes }}</p>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('customer-payments.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                Back to Payments
            </a>
            <a href="{{ route('customer-payments.edit', $customerPayment) }}" 
               style="background-color: #2563eb !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-block !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
               onmouseover="this.style.backgroundColor='#1d4ed8' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
               onmouseout="this.style.backgroundColor='#2563eb' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                Edit Payment
            </a>
        </div>
    </div>
</div>
@endsection


