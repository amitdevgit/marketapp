@extends('layouts.professional')

@section('title', 'Dashboard')

@section('header')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    <p class="mt-2 text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
</div>
@endsection

@section('content')
<!-- Welcome Card -->
<div class="bg-white rounded-xl shadow-lg p-8">
    <div class="flex items-center">
        <div class="h-16 w-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center mr-6">
            <span class="text-2xl font-bold text-white">{{ Auth::user()->name[0] }}</span>
        </div>
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-gray-600 mt-1">Here's your business overview</p>
        </div>
    </div>
</div>
@endsection