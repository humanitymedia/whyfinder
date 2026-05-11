<x-admin-layout>
    <x-slot name="header">Edit User</x-slot>

    <div class="max-w-2xl">
        {{-- Back link --}}
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1 text-sm text-brand-gray hover:text-brand-dark transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Users
        </a>

        {{-- User info header --}}
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 rounded-full bg-brand-blush flex items-center justify-center text-xl font-bold text-brand-brown">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-brand-dark">{{ $user->name }}</h2>
                <p class="text-sm text-brand-gray">Member since {{ $user->created_at->format('F j, Y') }}</p>
            </div>
        </div>

        {{-- Edit form --}}
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-semibold text-brand-dark">User Details</h3>

                <div>
                    <label for="name" class="block text-sm font-medium text-brand-dark mb-1">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-brand-dark mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-semibold text-brand-dark">Role Assignment</h3>
                <p class="text-sm text-brand-gray">Changing the role will update the user's permissions across the platform.</p>

                <div class="grid grid-cols-2 gap-3">
                    @foreach($roles as $role)
                        <label class="relative flex items-center gap-3 p-4 rounded-lg border cursor-pointer transition-colors
                            {{ (old('role', $user->roles->first()?->name) === $role) ? 'border-brand-red bg-red-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="role" value="{{ $role }}"
                                   {{ (old('role', $user->roles->first()?->name) === $role) ? 'checked' : '' }}
                                   class="text-brand-red focus:ring-brand-red">
                            <div>
                                <span class="text-sm font-medium text-brand-dark capitalize">{{ $role }}</span>
                                <p class="text-xs text-brand-gray mt-0.5">
                                    @if($role === 'admin') Full platform access
                                    @elseif($role === 'manager') Content & community moderation
                                    @elseif($role === 'instructor') Course creation & management
                                    @else Course enrollment & learning
                                    @endif
                                </p>
                            </div>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('role')" class="mt-1" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Save Changes
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 text-sm font-medium text-brand-gray border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
