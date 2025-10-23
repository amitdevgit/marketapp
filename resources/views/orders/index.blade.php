@extends('layouts.professional')

@section('title', 'Orders')

@section('header')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
    <p class="mt-2 text-sm text-gray-600">Manage customer orders</p>
</div>
@endsection

@section('content')
<!-- Orders Content -->
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Order Management</h2>
        <div class="flex space-x-2">
            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                Export
            </button>
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                New Order
            </button>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#1234</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">John Doe</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">iPhone 15 Pro</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$999</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-15</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#1235</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Jane Smith</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Samsung Galaxy S24</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$799</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Processing</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-14</td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#1236</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Mike Johnson</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">MacBook Pro M3</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$1,999</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pending</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024-01-13</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection


