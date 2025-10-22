@extends('layouts.professional')

@section('title', 'Add New Merchant')

<style>
.submit-btn {
    background-color: #059669 !important;
    color: white !important;
    border: none !important;
    padding: 12px 24px !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}

.submit-btn:hover {
    background-color: #047857 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3) !important;
}
</style>

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('merchants.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add New Merchant</h1>
            <p class="mt-2 text-sm text-gray-600">Add a new vegetable supplier to your network.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-lg p-8">
    <form action="{{ route('merchants.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Personal Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Merchant Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('name') border-red-500 @enderror"
                    placeholder="Enter merchant name"
                    required
                />
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('email') border-red-500 @enderror"
                    placeholder="Enter email address (optional)"
                />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Phone Number <span class="text-red-500">*</span>
                </label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('phone') border-red-500 @enderror"
                    placeholder="Enter phone number"
                    required
                />
                @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Business Name <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="business_name" 
                    name="business_name" 
                    value="{{ old('business_name') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('business_name') border-red-500 @enderror"
                    placeholder="Enter business name"
                    required
                />
                @error('business_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Address Information -->
        <div>
            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                Address <span class="text-red-500">*</span>
            </label>
            <textarea 
                id="address" 
                name="address" 
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('address') border-red-500 @enderror"
                placeholder="Enter complete address"
                required
            >{{ old('address') }}</textarea>
            @error('address')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Business Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="gst_number" class="block text-sm font-medium text-gray-700 mb-2">
                    GST Number
                </label>
                <input 
                    type="text" 
                    id="gst_number" 
                    name="gst_number" 
                    value="{{ old('gst_number') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('gst_number') border-red-500 @enderror"
                    placeholder="Enter GST number (optional)"
                />
                @error('gst_number')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    value="1"
                    {{ old('is_active', true) ? 'checked' : '' }}
                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active Merchant
                </label>
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
                placeholder="Any additional notes about this merchant"
            >{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('merchants.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" 
                    style="background-color: #059669 !important; color: white !important; padding: 12px 24px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; display: inline-block !important; cursor: pointer !important; border: none !important; font-size: 14px !important; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;"
                    onmouseover="this.style.backgroundColor='#047857' !important; this.style.boxShadow='0 6px 8px rgba(0, 0, 0, 0.15)' !important;"
                    onmouseout="this.style.backgroundColor='#059669' !important; this.style.boxShadow='0 4px 6px rgba(0, 0, 0, 0.1)' !important;">
                Add Merchant
            </button>
        </div>
    </form>
</div>
@endsection
