@extends('layouts.professional')

@section('title', 'Profile Settings')

@section('header')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Profile Settings</h1>
    <p class="mt-2 text-sm text-gray-600">Manage your account settings and preferences.</p>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Profile Information -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="h-12 w-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Profile Information</h2>
                <p class="text-sm text-gray-600">Update your account's profile information and email address.</p>
            </div>
        </div>
        
        @include('profile.partials.update-profile-information-form')
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="h-12 w-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Change Password</h2>
                <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
            </div>
        </div>
        
        @include('profile.partials.update-password-form')
    </div>

    <!-- Delete Account -->
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="h-12 w-12 bg-gradient-to-r from-red-500 to-pink-600 rounded-lg flex items-center justify-center mr-4">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Delete Account</h2>
                <p class="text-sm text-gray-600">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
        </div>
        
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection