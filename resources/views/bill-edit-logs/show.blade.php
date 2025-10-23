@extends('layouts.professional')

@section('title', 'Bill Edit Log Details')

@section('header')
<div class="mb-8">
    <div class="flex items-center">
        <a href="{{ route('bill-edit-logs.index') }}" class="mr-4 text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Log Details</h1>
            <p class="mt-2 text-sm text-gray-600">Detailed view of bill edit activity.</p>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <!-- Log Header -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ ucfirst($billEditLog->action) }} - {{ ucfirst(str_replace('_', ' ', $billEditLog->bill_type)) }} #{{ $billEditLog->bill_id }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $billEditLog->created_at->format('M d, Y \a\t h:i A') }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $billEditLog->bill_type == 'merchant_bill' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst(str_replace('_', ' ', $billEditLog->bill_type)) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $billEditLog->action == 'created' ? 'bg-green-100 text-green-800' : ($billEditLog->action == 'updated' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($billEditLog->action) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- User Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">User Information</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">User:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $billEditLog->user ? $billEditLog->user->name : 'System' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Email:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $billEditLog->user ? $billEditLog->user->email : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">IP Address:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $billEditLog->ip_address ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Technical Details</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">User Agent:</span>
                        <span class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $billEditLog->user_agent ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Log ID:</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $billEditLog->id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Changes Summary -->
        @if($billEditLog->changes_summary)
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Changes Summary</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800">{{ $billEditLog->changes_summary }}</p>
            </div>
        </div>
        @endif

        <!-- Data Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Old Data -->
            @if($billEditLog->old_data)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Previous Data</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap overflow-x-auto">{{ json_encode($billEditLog->old_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            <!-- New Data -->
            @if($billEditLog->new_data)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Updated Data</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <pre class="text-xs text-gray-700 whitespace-pre-wrap overflow-x-auto">{{ json_encode($billEditLog->new_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <a href="{{ route('bill-edit-logs.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                    Back to Logs
                </a>
                
                @if($billEditLog->bill_type == 'merchant_bill')
                    <a href="{{ route('merchant-bills.show', $billEditLog->bill_id) }}" class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                        View Bill
                    </a>
                @elseif($billEditLog->bill_type == 'customer_bill')
                    <a href="{{ route('customer-bills.show', $billEditLog->bill_id) }}" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        View Bill
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


