@extends('layouts.professional')

@section('title', 'Product Details')

@section('header')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('products.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $product->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Product details and inventory information.</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-lg hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Information -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Product Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-green-400 to-emerald-500 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-xl font-bold text-white">{{ substr($product->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Product Information</h3>
                    <p class="text-sm text-gray-600">Basic product details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Product Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $product->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Product Type</label>
                    @if($product->product_type === 'vegetables')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Vegetables
                        </span>
                    @elseif($product->product_type === 'fruits')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            Fruits
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Other
                        </span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Unit</label>
                    <p class="text-lg text-gray-900">{{ $product->unit }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    @if($product->is_active)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        @if($product->notes)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-gray-400 to-gray-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Additional Information</h3>
                    <p class="text-sm text-gray-600">Notes and details</p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Notes</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $product->notes }}</p>
                </div>
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
                <a href="{{ route('products.edit', $product) }}" class="w-full flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                    <svg class="h-5 w-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Edit Product</span>
                </a>
                
                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                        <svg class="h-5 w-5 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="text-sm font-medium text-red-800">Delete Product</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Product Stats</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Created</span>
                    <span class="text-sm font-medium text-gray-900">{{ $product->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Updated</span>
                    <span class="text-sm font-medium text-gray-900">{{ $product->updated_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    @if($product->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Stock Status</span>
                    @if($product->stock_quantity <= $product->min_stock_level)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Low Stock
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            In Stock
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
