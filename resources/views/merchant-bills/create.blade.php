@extends('layouts.professional')

@section('title', 'Create Merchant Bill')

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('merchant-bills.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Merchant Bill</h1>
            <p class="mt-2 text-sm text-gray-600">Create a new bill for merchant products.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-full mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
    <form id="merchantBillForm" action="{{ route('merchant-bills.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Merchant Selection -->
        <div>
            <label for="merchant_id" class="block text-sm font-medium text-gray-700 mb-2">
                Merchant <span class="text-red-500">*</span>
            </label>
            <select 
                id="merchant_id" 
                name="merchant_id" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('merchant_id') border-red-500 @enderror"
                required
            >
                <option value="">Select Merchant</option>
                @foreach($merchants as $merchant)
                    <option value="{{ $merchant->id }}" {{ old('merchant_id') == $merchant->id ? 'selected' : '' }}>
                        {{ $merchant->name }} - {{ $merchant->business_name }}
                    </option>
                @endforeach
            </select>
            @error('merchant_id')
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
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('bill_date') border-red-500 @enderror"
                required
            />
            @error('bill_date')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bill Items -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Bill Items</h3>
                <button type="button" id="addItemBtn" 
                        style="background-color: #059669 !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; font-weight: 500 !important; display: inline-flex !important; align-items: center !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;"
                        onmouseover="this.style.backgroundColor='#047857' !important; this.style.boxShadow='0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)' !important;"
                        onmouseout="this.style.backgroundColor='#059669' !important; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)' !important;">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: white !important;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Row
                </button>
            </div>
            
            <!-- Proper Table Format -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-visible">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Table Header -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Misc Adj.</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Rate (₹)</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total (₹)</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    
                    <!-- Table Body -->
                    <tbody id="billItems" class="bg-white divide-y divide-gray-200">
                        <!-- Item rows will be added here dynamically -->
                    </tbody>
                </table>
                
                <!-- Empty State -->
                <div id="emptyState" class="px-6 py-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <p class="text-sm">No items added yet. Click "Add Row" to get started.</p>
                </div>
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
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('notes') border-red-500 @enderror"
                placeholder="Any additional notes about this bill"
            >{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Total Amount Display -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                <span id="totalAmount" class="text-2xl font-bold text-green-600">₹0.00</span>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('merchant-bills.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" 
                    style="background-color: #059669 !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-block !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
                    onmouseover="this.style.backgroundColor='#047857' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
                    onmouseout="this.style.backgroundColor='#059669' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                Create Bill
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const customers = @json($customers);
    const products = @json($products);
    let itemIndex = 0;

    // Add first item row
    addItemRow();

    // Add item button click handler
    document.getElementById('addItemBtn').addEventListener('click', addItemRow);

    function addItemRow() {
        const container = document.getElementById('billItems');
        const emptyState = document.getElementById('emptyState');
        
        // Hide empty state when adding first item
        if (emptyState) {
            emptyState.style.display = 'none';
        }
        
        const itemRow = document.createElement('tr');
        itemRow.className = 'bill-item-row hover:bg-gray-50 transition-colors duration-200';
        itemRow.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap w-1/3">
                <div class="relative">
                    <input type="text" class="customer-search w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white" placeholder="Search or select customer..." autocomplete="off">
                    <input type="hidden" name="items[${itemIndex}][customer_id]" class="customer-id-input" required>
                    <div class="customer-dropdown absolute z-[9999] w-full mt-1 bg-white border border-gray-300 rounded-md shadow-xl hidden max-h-80 overflow-y-auto min-w-max" style="position: absolute !important; z-index: 9999 !important;">
                        ${customers.map(customer => `<div class="px-4 py-3 hover:bg-gray-100 cursor-pointer customer-option text-sm border-b border-gray-100 last:border-b-0" data-id="${customer.id}" data-name="${customer.name}">${customer.name}</div>`).join('')}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap w-1/3">
                <div class="relative">
                    <input type="text" class="product-search w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white" placeholder="Search or select product..." autocomplete="off">
                    <input type="hidden" name="items[${itemIndex}][product_id]" class="product-id-input" required>
                    <div class="product-dropdown absolute z-[9999] w-full mt-1 bg-white border border-gray-300 rounded-md shadow-xl hidden max-h-80 overflow-y-auto min-w-max" style="position: absolute !important; z-index: 9999 !important;">
                        ${products.map(product => `<div class="px-4 py-3 hover:bg-gray-100 cursor-pointer product-option text-sm border-b border-gray-100 last:border-b-0" data-id="${product.id}" data-name="${product.name}" data-unit="${product.unit}">${product.name} (${product.unit})</div>`).join('')}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right w-20">
                <input type="number" name="items[${itemIndex}][quantity]" step="0.01" min="0" class="w-16 px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right quantity-input" placeholder="0.00" required>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right w-20">
                <input type="number" name="items[${itemIndex}][weight]" step="0.01" min="0" class="w-16 px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right weight-input" placeholder="0.00" required>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right w-20">
                <input type="number" name="items[${itemIndex}][misc_adjustment]" step="0.01" class="w-16 px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right misc-input" value="0" placeholder="0.00">
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right w-20">
                <input type="number" name="items[${itemIndex}][rate]" step="0.01" min="0" class="w-16 px-2 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 text-right rate-input" placeholder="0.00" required>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
                <span class="text-sm font-medium text-gray-900 net-quantity-display">0.00</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right">
                <span class="text-sm font-semibold text-gray-900 total-display">₹0.00</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-center">
                <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors duration-200 remove-item-btn" title="Remove Row">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </td>
        `;
        
        container.appendChild(itemRow);
        itemIndex++;

        // Add event listeners for calculations
        const quantityInput = itemRow.querySelector('.quantity-input');
        const weightInput = itemRow.querySelector('.weight-input');
        const rateInput = itemRow.querySelector('.rate-input');
        const miscInput = itemRow.querySelector('.misc-input');
        const netQuantityDisplay = itemRow.querySelector('.net-quantity-display');
        const totalDisplay = itemRow.querySelector('.total-display');
        const removeBtn = itemRow.querySelector('.remove-item-btn');

        // Add searchable dropdown functionality
        const customerSearch = itemRow.querySelector('.customer-search');
        const customerDropdown = itemRow.querySelector('.customer-dropdown');
        const customerIdInput = itemRow.querySelector('.customer-id-input');
        const customerOptions = itemRow.querySelectorAll('.customer-option');

        const productSearch = itemRow.querySelector('.product-search');
        const productDropdown = itemRow.querySelector('.product-dropdown');
        const productIdInput = itemRow.querySelector('.product-id-input');
        const productOptions = itemRow.querySelectorAll('.product-option');

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

        // Product search functionality
        productSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            productDropdown.classList.remove('hidden');
            
            productOptions.forEach(option => {
                const name = option.dataset.name.toLowerCase();
                if (name.includes(searchTerm)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        productSearch.addEventListener('focus', function() {
            productDropdown.classList.remove('hidden');
        });

        productOptions.forEach(option => {
            option.addEventListener('click', function() {
                productSearch.value = `${this.dataset.name} (${this.dataset.unit})`;
                productIdInput.value = this.dataset.id;
                productDropdown.classList.add('hidden');
            });
        });

        // Hide dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!itemRow.contains(e.target)) {
                customerDropdown.classList.add('hidden');
                productDropdown.classList.add('hidden');
            }
        });

                function calculateTotal() {
                    const weight = parseFloat(weightInput.value) || 0;
                    const rate = parseFloat(rateInput.value) || 0;
                    const misc = parseFloat(miscInput.value) || 0;
                    const netQuantity = weight - misc; // Net Qty = Weight - Misc Adj
                    const total = netQuantity * rate; // Total = Net Qty × Rate
                    
                    netQuantityDisplay.textContent = netQuantity.toFixed(2);
                    totalDisplay.textContent = `₹${total.toFixed(2)}`;
                    updateGrandTotal();
                }

        quantityInput.addEventListener('input', calculateTotal);
        weightInput.addEventListener('input', calculateTotal);
        rateInput.addEventListener('input', calculateTotal);
        miscInput.addEventListener('input', calculateTotal);

        removeBtn.addEventListener('click', function() {
            itemRow.remove();
            updateGrandTotal();
            
            // Show empty state if no items left
            if (container.children.length === 0 && emptyState) {
                emptyState.style.display = 'block';
            }
        });
    }

    function updateGrandTotal() {
        const totalDisplays = document.querySelectorAll('.total-display');
        let grandTotal = 0;
        
        totalDisplays.forEach(display => {
            const value = display.textContent.replace('₹', '').replace(',', '');
            grandTotal += parseFloat(value) || 0;
        });
        
        document.getElementById('totalAmount').textContent = `₹${grandTotal.toFixed(2)}`;
    }
});
</script>
@endsection
