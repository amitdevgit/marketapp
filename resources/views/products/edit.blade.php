@extends('layouts.professional')

@section('title', 'Edit Product')

<style>
.submit-btn, input[type="submit"] {
    background-color: #059669 !important;
    color: white !important;
    border: none !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    display: inline-block !important;
    text-decoration: none !important;
    font-size: 14px !important;
    line-height: 1.5 !important;
    min-width: 120px !important;
    box-shadow: none !important;
    outline: none !important;
}

.submit-btn:hover, input[type="submit"]:hover {
    background-color: #047857 !important;
    color: white !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3) !important;
}

.submit-btn:focus, input[type="submit"]:focus {
    outline: none !important;
    box-shadow: 0 0 0 2px #059669 !important;
    background-color: #059669 !important;
    color: white !important;
}

.submit-btn:active, input[type="submit"]:active {
    background-color: #047857 !important;
    color: white !important;
}
</style>

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('products.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
            <p class="mt-2 text-sm text-gray-600">Update product information.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8">
    <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Product Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Product Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $product->name) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                    placeholder="Enter product name"
                    required
                />
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="product_type" class="block text-sm font-medium text-gray-700 mb-2">
                    Product Type <span class="text-red-500">*</span>
                </label>
                <select 
                    id="product_type" 
                    name="product_type" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('product_type') border-red-500 @enderror"
                    required
                >
                    <option value="">Select product type</option>
                    <option value="vegetables" {{ old('product_type', $product->product_type) == 'vegetables' ? 'selected' : '' }}>Vegetables</option>
                    <option value="fruits" {{ old('product_type', $product->product_type) == 'fruits' ? 'selected' : '' }}>Fruits</option>
                    <option value="other" {{ old('product_type', $product->product_type) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('product_type')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Unit -->
        <div>
            <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                Unit <span class="text-red-500">*</span>
            </label>
            <select 
                id="unit" 
                name="unit" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('unit') border-red-500 @enderror"
                required
            >
                <option value="">Select unit</option>
                <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                <option value="piece" {{ old('unit', $product->unit) == 'piece' ? 'selected' : '' }}>Piece</option>
                <option value="bunch" {{ old('unit', $product->unit) == 'bunch' ? 'selected' : '' }}>Bunch</option>
                <option value="dozen" {{ old('unit', $product->unit) == 'dozen' ? 'selected' : '' }}>Dozen</option>
                <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                <option value="bag" {{ old('unit', $product->unit) == 'bag' ? 'selected' : '' }}>Bag</option>
                <option value="tray" {{ old('unit', $product->unit) == 'tray' ? 'selected' : '' }}>Tray</option>
            </select>
            @error('unit')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Notes and Status -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('notes') border-red-500 @enderror"
                    placeholder="Any additional notes about this product"
                >{{ old('notes', $product->notes) }}</textarea>
                @error('notes')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    value="1"
                    {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active Product
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('products.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" 
                    style="background-color: #059669; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 500; display: inline-block; cursor: pointer; border: none; font-size: 14px;"
                    onmouseover="this.style.backgroundColor='#047857'"
                    onmouseout="this.style.backgroundColor='#059669'">
                Update Product
            </button>
        </div>
    </form>
</div>
@endsection
