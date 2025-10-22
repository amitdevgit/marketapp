@extends('layouts.professional')

@section('title', 'Generate Customer Bill')

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
                <h1 class="text-3xl font-bold text-gray-900">Generate Customer Bill</h1>
                <p class="mt-2 text-sm text-gray-600">Select a customer and generate bill from merchant billing data.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-full mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <form id="customerBillForm" action="{{ route('customer-bills.store') }}" method="POST" class="space-y-6">
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
                            <div class="px-4 py-3 hover:bg-gray-100 cursor-pointer customer-option text-sm border-b border-gray-100 last:border-b-0" data-id="{{ $customer->id }}" data-name="{{ $customer->name }}">
                                <div class="font-medium">{{ $customer->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $customer->phone }} • {{ ucfirst($customer->customer_type) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('customer_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bill Date -->
            <div>
                <label for="bill_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Bill Date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    id="bill_date" 
                    name="bill_date" 
                    value="{{ old('bill_date', date('Y-m-d')) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('bill_date') border-red-500 @enderror"
                    required
                />
                @error('bill_date')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Customer Products Preview -->
            <div id="customer_products_section" class="hidden">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">Available Products from All Merchant Bills</h3>
                <div id="products_preview" class="bg-gray-50 rounded-lg p-4">
                    <!-- Products will be loaded here via AJAX -->
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('notes') border-red-500 @enderror"
                    placeholder="Any additional notes for this bill"
                >{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('customer-bills.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Cancel
                </a>
                <button type="submit" 
                        id="generate_bill_btn"
                        disabled
                        style="background-color: #2563eb !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-block !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important; opacity: 0.5 !important;"
                        onmouseover="if(!this.disabled) { this.style.backgroundColor='#1d4ed8' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important; }"
                        onmouseout="if(!this.disabled) { this.style.backgroundColor='#2563eb' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important; }">
                    Generate Bill
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSearch = document.getElementById('customer_search');
    const customerDropdown = document.getElementById('customer_dropdown');
    const customerIdInput = document.getElementById('customer_id');
    const customerOptions = document.querySelectorAll('.customer-option');
    const customerProductsSection = document.getElementById('customer_products_section');
    const productsPreview = document.getElementById('products_preview');
    const generateBillBtn = document.getElementById('generate_bill_btn');
    const billDateInput = document.getElementById('bill_date');

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
            
            // Check if both customer and date are selected
            checkFormValidity();
            
            // Load customer products
            loadCustomerProducts(this.dataset.id);
        });
    });

    // Bill date change handler
    billDateInput.addEventListener('change', function() {
        // Check if both customer and date are selected
        checkFormValidity();
        
        // Reload products if customer is already selected
        if (customerIdInput.value) {
            loadCustomerProducts(customerIdInput.value);
        }
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!customerSearch.contains(e.target) && !customerDropdown.contains(e.target)) {
            customerDropdown.classList.add('hidden');
        }
    });

    // Function to check if form is valid (customer and date selected)
    function checkFormValidity() {
        const hasCustomer = customerIdInput.value && customerIdInput.value.trim() !== '';
        const hasDate = billDateInput.value && billDateInput.value.trim() !== '';
        
        if (hasCustomer && hasDate) {
            generateBillBtn.disabled = false;
            generateBillBtn.style.opacity = '1';
        } else {
            generateBillBtn.disabled = true;
            generateBillBtn.style.opacity = '0.5';
        }
    }

    function loadCustomerProducts(customerId) {
        const billDate = billDateInput.value;
        
        fetch(`{{ route('customer-bills.get-customer-products') }}?customer_id=${customerId}&bill_date=${billDate}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('Response data:', data); // Debug log
                
                if (data.error) {
                    productsPreview.innerHTML = `<p class="text-red-500 text-center py-4">${data.error}</p>`;
                    customerProductsSection.classList.remove('hidden');
                    return;
                }
                
                if (data.success && data.products && data.products.length > 0) {
                    displayCustomerProducts(data.products);
                    customerProductsSection.classList.remove('hidden');
                } else {
                    productsPreview.innerHTML = '<p class="text-gray-500 text-center py-4">No products found for this customer in merchant bills.</p>';
                    customerProductsSection.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading customer products:', error);
                productsPreview.innerHTML = '<p class="text-red-500 text-center py-4">Error loading products. Please try again.</p>';
                customerProductsSection.classList.remove('hidden');
            });
    }

    function displayCustomerProducts(products) {
        let html = '<div class="space-y-3">';
        
        products.forEach(product => {
            html += `
                <div class="bg-white rounded-lg p-4 border border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">${product.product_name}</h4>
                            <p class="text-sm text-gray-500">${product.product_unit} • From: ${product.merchant_name}</p>
                            <p class="text-xs text-gray-400">Merchant Bill Date: ${product.merchant_bill_date} (Bill ID: ${product.merchant_bill_id})</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">Qty: ${product.quantity}</p>
                            <p class="text-sm text-gray-600">Misc Adj: ${product.misc_adjustment || 0}</p>
                            <p class="text-sm text-gray-600">Net Qty: ${product.net_quantity}</p>
                            <p class="text-sm text-gray-600">Rate: ₹${product.rate}</p>
                            <p class="text-sm font-semibold text-green-600">Total: ₹${product.total_amount}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        
        const totalAmount = products.reduce((sum, product) => sum + parseFloat(product.total_amount), 0);
        html += `
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900">Total Products: ${products.length}</span>
                    <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                </div>
                <div class="flex justify-end">
                    <span class="text-xl font-bold text-green-600">₹${totalAmount.toFixed(2)}</span>
                </div>
            </div>
        `;
        
        productsPreview.innerHTML = html;
    }
});
</script>
@endsection
