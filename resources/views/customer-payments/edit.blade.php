@extends('layouts.professional')

@section('title', 'Edit Payment #' . $customerPayment->id)

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('customer-payments.show', $customerPayment) }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Payment #{{ $customerPayment->id }}</h1>
            <p class="mt-2 text-sm text-gray-600">Update payment information and details.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('customer-payments.update', $customerPayment) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Customer Selection -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Customer Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="customer_search" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" placeholder="Search or select customer..." autocomplete="off" value="{{ $customerPayment->customer->name }}">
                    <input type="hidden" id="customer_id" name="customer_id" value="{{ $customerPayment->customer_id }}" required>
                    <div id="customer_dropdown" class="absolute z-[9999] w-full mt-1 bg-white border border-gray-300 rounded-md shadow-xl hidden max-h-80 overflow-y-auto min-w-max" style="position: absolute !important; z-index: 9999 !important;">
                        @foreach($customers as $customer)
                            <div class="px-4 py-3 hover:bg-gray-100 cursor-pointer customer-option text-sm border-b border-gray-100 last:border-b-0" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}" data-balance="{{ $customer->balance }}" data-total-purchased="{{ $customer->total_purchased }}" data-total-paid="{{ $customer->total_paid }}">
                                <div class="font-medium">{{ $customer->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $customer->phone }} • {{ ucfirst($customer->customer_type) }}</div>
                                <div class="text-xs mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $customer->balance > 0 ? 'bg-red-100 text-red-800' : ($customer->balance < 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                        Balance: ₹{{ number_format($customer->balance, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('customer_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bill Selection -->
            <div>
                <label for="customer_bill_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Link to Bill (Optional)
                </label>
                <select id="customer_bill_id" name="customer_bill_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="">Select a bill (optional)</option>
                    @foreach($customerBills as $bill)
                        <option value="{{ $bill->id }}" {{ $customerPayment->customer_bill_id == $bill->id ? 'selected' : '' }}>
                            #{{ $bill->id }} - {{ $bill->bill_date->format('M d, Y') }} - ₹{{ number_format($bill->total_amount, 2) }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Select a specific bill to link this payment to, or leave blank for general payment.</p>
            </div>

            <!-- Payment Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Amount <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('amount') border-red-500 @enderror" placeholder="0.00" value="{{ $customerPayment->amount }}" required>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_method" name="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('payment_method') border-red-500 @enderror" required>
                        @foreach($paymentMethods as $value => $label)
                            <option value="{{ $value }}" {{ $customerPayment->payment_method == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Payment Date -->
            <div>
                <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Payment Date <span class="text-red-500">*</span>
                </label>
                <input type="date" id="payment_date" name="payment_date" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('payment_date') border-red-500 @enderror" value="{{ $customerPayment->payment_date->format('Y-m-d') }}" required>
                @error('payment_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('notes') border-red-500 @enderror" placeholder="Any additional notes about this payment">{{ $customerPayment->notes }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Balance Display -->
            <div id="customer_balance_display" class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Customer Balance Information</h3>
                            <p class="text-sm text-gray-600">Current outstanding balance</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div id="customer_balance_amount" class="text-3xl font-bold text-blue-600">₹{{ number_format($customerPayment->customer->balance, 2) }}</div>
                        <div class="text-sm text-gray-500">Outstanding</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Total Purchased</div>
                        <div id="total_purchased" class="text-lg font-semibold text-gray-900">₹{{ number_format($customerPayment->customer->total_purchased, 2) }}</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Total Paid</div>
                        <div id="total_paid" class="text-lg font-semibold text-green-600">₹{{ number_format($customerPayment->customer->total_paid, 2) }}</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Remaining Balance</div>
                        <div id="remaining_balance" class="text-lg font-semibold text-red-600">₹{{ number_format($customerPayment->customer->balance, 2) }}</div>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-yellow-800">Balance After Payment:</div>
                            <div id="balance_after_payment" class="text-lg font-bold text-yellow-900">₹{{ number_format($customerPayment->customer->balance - $customerPayment->amount, 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('customer-payments.show', $customerPayment) }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        style="background-color: #2563eb !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-block !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
                        onmouseover="this.style.backgroundColor='#1d4ed8' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
                        onmouseout="this.style.backgroundColor='#2563eb' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                    Update Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customers = @json($customers);
    const customerSearch = document.getElementById('customer_search');
    const customerIdInput = document.getElementById('customer_id');
    const customerDropdown = document.getElementById('customer_dropdown');
    const customerOptions = document.querySelectorAll('#customer_dropdown .customer-option');

    // Customer search functionality
    customerSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        customerDropdown.classList.remove('hidden');
        
        customerOptions.forEach(option => {
            const name = option.dataset.name.toLowerCase();
            if (name.includes(searchTerm)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });

    customerSearch.addEventListener('focus', function() {
        customerDropdown.classList.remove('hidden');
    });

    customerOptions.forEach(option => {
        option.addEventListener('click', function() {
            customerSearch.value = this.dataset.name;
            customerIdInput.value = this.dataset.id;
            customerDropdown.classList.add('hidden');
        });
    });

    // Hide dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerSearch.contains(e.target) && !customerDropdown.contains(e.target)) {
            customerDropdown.classList.add('hidden');
        }
    });
});
</script>
@endsection
