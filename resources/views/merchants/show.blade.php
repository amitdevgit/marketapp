@extends('layouts.professional')

@section('title', 'Merchant Details')

@section('header')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('merchants.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $merchant->name }}</h1>
                <p class="mt-2 text-sm text-gray-600">Merchant details and information.</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('merchants.edit', $merchant) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium rounded-lg hover:from-yellow-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-all duration-200 shadow-lg hover:shadow-xl">
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
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-xl font-bold text-white">{{ substr($merchant->name, 0, 1) }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
                    <p class="text-sm text-gray-600">Basic merchant details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $merchant->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                    <p class="text-lg text-gray-900">{{ $merchant->email ?: 'Not provided' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                    <p class="text-lg text-gray-900">{{ $merchant->phone }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    @if($merchant->is_active)
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

        <!-- Business Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Business Information</h3>
                    <p class="text-sm text-gray-600">Company and business details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Business Name</label>
                    <p class="text-lg font-semibold text-gray-900">{{ $merchant->business_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">GST Number</label>
                    <p class="text-lg text-gray-900">{{ $merchant->gst_number ?: 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-indigo-400 to-blue-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Address Information</h3>
                    <p class="text-sm text-gray-600">Location and contact details</p>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Address</label>
                <p class="text-lg text-gray-900">{{ $merchant->address }}</p>
            </div>
        </div>

        <!-- Notes -->
        @if($merchant->notes)
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="h-12 w-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Notes</h3>
                    <p class="text-sm text-gray-600">Additional information</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-900 whitespace-pre-wrap">{{ $merchant->notes }}</p>
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
                <a href="{{ route('merchants.edit', $merchant) }}" class="w-full flex items-center p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                    <svg class="h-5 w-5 text-yellow-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="text-sm font-medium text-yellow-800">Edit Merchant</span>
                </a>
                
                <form action="{{ route('merchants.destroy', $merchant) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this merchant?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full flex items-center p-3 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                        <svg class="h-5 w-5 text-red-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="text-sm font-medium text-red-800">Delete Merchant</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Merchant Stats -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Merchant Stats</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Created</span>
                    <span class="text-sm font-medium text-gray-900">{{ $merchant->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Updated</span>
                    <span class="text-sm font-medium text-gray-900">{{ $merchant->updated_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status</span>
                    @if($merchant->is_active)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
