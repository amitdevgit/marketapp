@extends('layouts.professional')

@section('title', 'Record Customer Payment')

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('customer-payments.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Record Customer Payment</h1>
            <p class="mt-2 text-sm text-gray-600">Record a payment received from a customer.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form action="{{ route('customer-payments.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Customer Selection -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Customer Name <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="customer_search" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200" placeholder="Search or select customer..." autocomplete="off">
                    <input type="hidden" id="customer_id" name="customer_id" required>
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
            <div id="bill_selection_section" class="hidden">
                <label for="customer_bill_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Link to Bill (Optional)
                </label>
                <select id="customer_bill_id" name="customer_bill_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                    <option value="">Select a bill (optional)</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">Select a specific bill to link this payment to, or leave blank for general payment.</p>
            </div>

            <!-- Payment Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Amount <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('amount') border-red-500 @enderror" placeholder="0.00" required>
                    @error('amount')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_method" name="payment_method" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('payment_method') border-red-500 @enderror" required>
                        <option value="">Select payment method</option>
                        @foreach($paymentMethods as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
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
                <input type="date" id="payment_date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('payment_date') border-red-500 @enderror" required>
                @error('payment_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('notes') border-red-500 @enderror" placeholder="Any additional notes about this payment"></textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Balance Display -->
            <div id="customer_balance_display" class="hidden bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
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
                        <div id="customer_balance_amount" class="text-3xl font-bold text-blue-600">₹0.00</div>
                        <div class="text-sm text-gray-500">Outstanding</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Total Purchased</div>
                        <div id="total_purchased" class="text-lg font-semibold text-gray-900">₹0.00</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Total Paid</div>
                        <div id="total_paid" class="text-lg font-semibold text-green-600">₹0.00</div>
                    </div>
                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                        <div class="text-xs text-gray-500 uppercase tracking-wide">Remaining Balance</div>
                        <div id="remaining_balance" class="text-lg font-semibold text-red-600">₹0.00</div>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <div class="text-sm font-medium text-yellow-800">Balance After Payment:</div>
                            <div id="balance_after_payment" class="text-lg font-bold text-yellow-900">₹0.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('customer-payments.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        style="background-color: #2563eb !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-block !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
                        onmouseover="this.style.backgroundColor='#1d4ed8' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
                        onmouseout="this.style.backgroundColor='#2563eb' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                    Record Payment
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
    const billSelectionSection = document.getElementById('bill_selection_section');
    const customerBalanceDisplay = document.getElementById('customer_balance_display');
    const customerBalanceAmount = document.getElementById('customer_balance_amount');
    const balanceAfterPayment = document.getElementById('balance_after_payment');
    const totalPurchased = document.getElementById('total_purchased');
    const totalPaid = document.getElementById('total_paid');
    const remainingBalance = document.getElementById('remaining_balance');
    const amountInput = document.getElementById('amount');
    

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
            
            // Show bill selection and balance
            billSelectionSection.classList.remove('hidden');
            customerBalanceDisplay.classList.remove('hidden');
            
            // Get customer data and fetch fresh balance
            const customerId = this.dataset.id;
            const customer = customers.find(c => c.id == customerId);
            
            if (customer) {
                // Fetch fresh customer data to get updated balance
                fetchCustomerBalance(customerId);
            }
            
            // Load customer bills
            loadCustomerBills(customerId);
        });
    });

    // Amount input change handler
    amountInput.addEventListener('input', updateBalanceAfterPayment);

    function updateBalanceAfterPayment() {
        const balance = parseFloat(customerBalanceAmount.textContent.replace('₹', '').replace(',', '')) || 0;
        const amount = parseFloat(amountInput.value) || 0;
        const newBalance = balance - amount;
        
        balanceAfterPayment.textContent = `₹${newBalance.toFixed(2)}`;
        
        // Update the balance after payment color
        const balanceAfterElement = balanceAfterPayment.parentElement.parentElement;
        if (newBalance < 0) {
            balanceAfterElement.className = 'mt-4 p-3 bg-green-50 border border-green-200 rounded-lg';
            balanceAfterPayment.className = 'text-lg font-bold text-green-900';
        } else if (newBalance > 0) {
            balanceAfterElement.className = 'mt-4 p-3 bg-red-50 border border-red-200 rounded-lg';
            balanceAfterPayment.className = 'text-lg font-bold text-red-900';
        } else {
            balanceAfterElement.className = 'mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg';
            balanceAfterPayment.className = 'text-lg font-bold text-gray-900';
        }
    }

    function fetchCustomerBalance(customerId) {
        fetch(`/api/customers/${customerId}/balance`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update all balance displays with fresh data
                const balance = parseFloat(data.customer.balance);
                const totalPurchasedAmount = parseFloat(data.customer.total_purchased);
                const totalPaidAmount = parseFloat(data.customer.total_paid);
                
                customerBalanceAmount.textContent = `₹${balance.toFixed(2)}`;
                totalPurchased.textContent = `₹${totalPurchasedAmount.toFixed(2)}`;
                totalPaid.textContent = `₹${totalPaidAmount.toFixed(2)}`;
                remainingBalance.textContent = `₹${balance.toFixed(2)}`;
                
                // Update balance color based on amount
                if (balance > 0) {
                    customerBalanceAmount.className = 'text-3xl font-bold text-red-600';
                    remainingBalance.className = 'text-lg font-semibold text-red-600';
                } else if (balance < 0) {
                    customerBalanceAmount.className = 'text-3xl font-bold text-green-600';
                    remainingBalance.className = 'text-lg font-semibold text-green-600';
                } else {
                    customerBalanceAmount.className = 'text-3xl font-bold text-gray-600';
                    remainingBalance.className = 'text-lg font-semibold text-gray-600';
                }
                
                // Update balance after payment calculation
                updateBalanceAfterPayment();
            }
        })
        .catch(error => {
            console.error('Error fetching customer balance:', error);
            // Fallback to cached data if API fails
            const customer = customers.find(c => c.id == customerId);
            if (customer) {
                const balance = parseFloat(customer.balance);
                const totalPurchasedAmount = parseFloat(customer.total_purchased);
                const totalPaidAmount = parseFloat(customer.total_paid);
                
                customerBalanceAmount.textContent = `₹${balance.toFixed(2)}`;
                totalPurchased.textContent = `₹${totalPurchasedAmount.toFixed(2)}`;
                totalPaid.textContent = `₹${totalPaidAmount.toFixed(2)}`;
                remainingBalance.textContent = `₹${balance.toFixed(2)}`;
                
                // Update balance color based on amount
                if (balance > 0) {
                    customerBalanceAmount.className = 'text-3xl font-bold text-red-600';
                    remainingBalance.className = 'text-lg font-semibold text-red-600';
                } else if (balance < 0) {
                    customerBalanceAmount.className = 'text-3xl font-bold text-green-600';
                    remainingBalance.className = 'text-lg font-semibold text-green-600';
                } else {
                    customerBalanceAmount.className = 'text-3xl font-bold text-gray-600';
                    remainingBalance.className = 'text-lg font-semibold text-gray-600';
                }
                
                updateBalanceAfterPayment();
            }
        });
    }

    function loadCustomerBills(customerId) {
        fetch(`{{ route('customer-payments.get-customer-bills') }}?customer_id=${customerId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const billSelect = document.getElementById('customer_bill_id');
            billSelect.innerHTML = '<option value="">Select a bill (optional)</option>';
            
            if (data.success && data.bills && data.bills.length > 0) {
                data.bills.forEach(bill => {
                    const option = document.createElement('option');
                    option.value = bill.id;
                    option.textContent = `${bill.bill_number} - ${bill.bill_date} - ₹${bill.remaining_amount.toFixed(2)} remaining`;
                    billSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading customer bills:', error);
        });
    }

    // Hide dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerSearch.contains(e.target) && !customerDropdown.contains(e.target)) {
            customerDropdown.classList.add('hidden');
        }
    });
});
</script>
@endsection
