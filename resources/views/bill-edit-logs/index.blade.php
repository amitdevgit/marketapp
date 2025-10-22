@extends('layouts.professional')

@section('title', 'Bill Edit Logs')

@section('header')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <a href="{{ route('dashboard') }}" class="mr-4 text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Bill Edit Logs</h1>
                <p class="mt-2 text-sm text-gray-600">Track all bill modifications and activities.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-full mx-auto px-4">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="bill_type" class="block text-sm font-medium text-gray-700 mb-2">Bill Type</label>
                <select name="bill_type" id="bill_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    <option value="merchant_bill" {{ request('bill_type') == 'merchant_bill' ? 'selected' : '' }}>Merchant Bills</option>
                    <option value="customer_bill" {{ request('bill_type') == 'customer_bill' ? 'selected' : '' }}>Customer Bills</option>
                </select>
            </div>
            
            <div>
                <label for="action" class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                <select name="action" id="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Actions</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Activity Logs</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bill ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Changes</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $log->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $log->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->user ? $log->user->name : 'System' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->bill_type == 'merchant_bill' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $log->bill_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            #{{ $log->bill_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->action == 'created' ? 'bg-green-100 text-green-800' : ($log->action == 'updated' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                            {{ $log->changes_summary }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="{{ route('bill-edit-logs.show', $log) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm">No logs found matching your criteria.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
