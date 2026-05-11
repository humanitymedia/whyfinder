<x-guest-layout>
    <h2 class="text-2xl font-bold text-brand-dark text-center mb-1">Start your journey</h2>
    <p class="text-sm text-brand-gray text-center mb-8">Create your free account and discover your why.</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ password: '' }">
        @csrf

        <!-- First & Last Name -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-brand-dark mb-1">First name</label>
                <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                       class="w-full rounded-lg border-brand-gray-light px-4 py-3 text-sm focus:border-brand-red focus:ring-brand-red" placeholder="First name">
                <x-input-error :messages="$errors->get('first_name')" class="mt-1" />
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-brand-dark mb-1">Last name</label>
                <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required
                       class="w-full rounded-lg border-brand-gray-light px-4 py-3 text-sm focus:border-brand-red focus:ring-brand-red" placeholder="Last name">
                <x-input-error :messages="$errors->get('last_name')" class="mt-1" />
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-brand-dark mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="w-full rounded-lg border-brand-gray-light px-4 py-3 text-sm focus:border-brand-red focus:ring-brand-red" placeholder="you@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-brand-dark mb-1">Password</label>
            <div class="relative">
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                       x-model="password"
                       class="w-full rounded-lg border-brand-gray-light px-4 py-3 text-sm focus:border-brand-red focus:ring-brand-red pr-10" placeholder="Create a password">
                <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray hover:text-brand-dark">
                    <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <p class="text-xs text-brand-gray mt-1">Must be at least 8 characters.</p>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Hidden password confirmation (mirrors password field) -->
        <input type="hidden" name="password_confirmation" :value="password">

        <!-- Submit -->
        <button type="submit"
                class="w-full py-3 bg-brand-red text-white font-semibold rounded-full hover:bg-red-700 transition-colors text-sm">
            Create Account
        </button>

        <p class="text-xs text-brand-gray text-center">
            By creating an account, you agree to our
            <a href="#" class="text-brand-red hover:text-red-700">Terms of Service</a> and
            <a href="#" class="text-brand-red hover:text-red-700">Privacy Policy</a>.
        </p>
    </form>

    <p class="text-center text-sm text-brand-gray mt-6">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-brand-red hover:text-red-700 transition-colors">Log in</a>
    </p>
</x-guest-layout>
