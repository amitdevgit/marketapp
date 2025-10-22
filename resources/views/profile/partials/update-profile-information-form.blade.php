<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('patch')

    <!-- Name -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Name
        </label>
        <input 
            id="name" 
            name="name" 
            type="text" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('name') border-red-500 @enderror" 
            value="{{ old('name', $user->name) }}" 
            required 
            autofocus 
            autocomplete="name"
            placeholder="Enter your full name"
        />
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email
        </label>
        <input 
            id="email" 
            name="email" 
            type="email" 
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('email') border-red-500 @enderror" 
            value="{{ old('email', $user->email) }}" 
            required 
            autocomplete="username"
            placeholder="Enter your email address"
        />
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-3 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    Your email address is unverified.
                    <button form="send-verification" class="ml-1 underline text-sm text-yellow-600 hover:text-yellow-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Click here to re-send the verification email.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email address.
                    </p>
                @endif
            </div>
        @endif
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
            Save Changes
        </button>

        @if (session('status') === 'profile-updated')
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
                Profile updated successfully!
            </div>
        @endif
    </div>
</form>