<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MarketApp') }} - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
        
        .nav-item {
            transition: all 0.3s ease;
        }
        
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-item.active {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-72 md:flex-col">
            <div class="flex flex-col flex-grow pt-5 sidebar-gradient overflow-y-auto">
                <!-- Logo Section -->
                <div class="flex items-center flex-shrink-0 px-6 pb-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-white rounded-lg flex items-center justify-center mr-3">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">VeggieMarket</h1>
                            <p class="text-xs text-indigo-200">Vegetable Sales Management</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation -->
                <div class="flex-grow flex flex-col px-4">
                    <nav class="flex-1 space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'active text-white' : 'text-indigo-200' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            </svg>
                            Dashboard
                        </a>

                        <!-- Merchants -->
                        <a href="{{ route('merchants.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('merchants.*') ? 'active text-white' : 'text-indigo-200' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Merchants
                        </a>

                        <!-- Customers -->
                        <a href="{{ route('customers.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('customers.*') ? 'active text-white' : 'text-indigo-200' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Customers
                        </a>

                        <!-- Products -->
                        <a href="{{ route('products.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('products.*') ? 'active text-white' : 'text-indigo-200' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            Products
                        </a>

                            <!-- Billing -->
                            <a href="{{ route('merchant-bills.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('merchant-bills.*') ? 'active text-white' : 'text-indigo-200' }}">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Merchant Billing
                            </a>
                            <!-- Customer Billing -->
                            <a href="{{ route('customer-bills.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('customer-bills.*') ? 'active text-white' : 'text-indigo-200' }}">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Customer Billing
                            </a>

                            <!-- Customer Payments -->
                            <a href="{{ route('customer-payments.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('customer-payments.*') ? 'active text-white' : 'text-indigo-200' }}">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                Customer Payments
                            </a>
                            
                            <!-- Activity Logs -->
                            <a href="{{ route('bill-edit-logs.index') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('bill-edit-logs.*') ? 'active text-white' : 'text-indigo-200' }}">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Activity Logs
                            </a>

                        <!-- Profile Settings -->
                        <a href="{{ route('profile.edit') }}" class="nav-item flex items-center px-3 py-3 text-sm font-medium rounded-lg {{ request()->routeIs('profile.*') ? 'active text-white' : 'text-indigo-200' }}">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile Settings
                        </a>
                    </nav>
                </div>

                <!-- User Info -->
                <div class="flex-shrink-0 px-4 py-4 border-t border-indigo-400 border-opacity-20">
                    <div class="flex items-center">
                        <div class="h-10 w-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold text-white">{{ Auth::user()->name[0] }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-indigo-200">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <!-- Top navigation -->
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow-sm border-b border-gray-200">
                <button type="button" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                </button>
                <div class="flex-1 px-4 flex justify-between items-center">
                    <div class="flex-1 flex items-center">
                        <h2 class="text-xl font-semibold text-gray-900">@yield('title', 'Dashboard')</h2>
                    </div>
                    <div class="ml-4 flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-lg">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z" />
                            </svg>
                        </button>

                        <!-- Profile dropdown -->
                        <div class="relative">
                            <button type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="user-menu-button">
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ Auth::user()->name[0] }}</span>
                                </div>
                                <span class="ml-2 text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <svg class="ml-1 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none bg-gray-50">
                <div class="py-8">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @yield('header')
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>