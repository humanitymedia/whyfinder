<x-admin-layout>
    <x-slot name="header">Edit Instructor</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.instructors.index') }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">&larr; Back to Instructors</a>
    </div>

    <div class="max-w-2xl grid grid-cols-1 gap-6">
        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
                <p class="text-lg font-bold text-brand-dark">{{ $user->courses_count }}</p>
                <p class="text-sm text-brand-gray">Courses</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
                <p class="text-lg font-bold text-brand-dark">${{ number_format($user->total_earnings, 2) }}</p>
                <p class="text-sm text-brand-gray">Total Earnings</p>
            </div>
        </div>

        {{-- Edit form --}}
        <form method="POST" action="{{ route('admin.instructors.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Instructor Details</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Platform Fee Override (%)</label>
                        <div class="relative max-w-xs">
                            <input type="number" name="platform_fee_override"
                                   value="{{ old('platform_fee_override', $user->platform_fee_override) }}"
                                   placeholder="Leave blank for global ({{ $globalFee }}%)"
                                   min="0" max="100" step="0.01"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors pr-8">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray text-sm">%</span>
                        </div>
                        <p class="text-xs text-brand-gray mt-2">Leave empty to use the global rate of {{ $globalFee }}%. Set a custom value to override for this instructor only.</p>
                        @error('platform_fee_override') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
