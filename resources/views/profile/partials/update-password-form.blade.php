<form method="post" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    @method('put')

    <!-- Current Password -->
    <div>
        <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 mb-2">
            Current Password
        </label>
        <input 
            id="update_password_current_password" 
            name="current_password" 
            type="password" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('current_password', 'updatePassword') border-red-500 @enderror" 
            autocomplete="current-password"
            placeholder="Enter your current password"
        />
        @error('current_password', 'updatePassword')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- New Password -->
    <div>
        <label for="update_password_password" class="block text-sm font-medium text-gray-700 mb-2">
            New Password
        </label>
        <input 
            id="update_password_password" 
            name="password" 
            type="password" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('password', 'updatePassword') border-red-500 @enderror" 
            autocomplete="new-password"
            placeholder="Enter your new password"
        />
        @error('password', 'updatePassword')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Confirm Password -->
    <div>
        <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm New Password
        </label>
        <input 
            id="update_password_password_confirmation" 
            name="password_confirmation" 
            type="password" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
            autocomplete="new-password"
            placeholder="Confirm your new password"
        />
        @error('password_confirmation', 'updatePassword')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit Button -->
    <div class="flex items-center justify-between">
        <button 
            type="submit" 
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl"
        >
            <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Update Password
        </button>

        @if (session('status') === 'password-updated')
            <div 
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="flex items-center text-sm text-green-600 bg-green-50 px-4 py-2 rounded-lg"
            >
                <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Password updated successfully!
            </div>
        @endif
    </div>
</form>