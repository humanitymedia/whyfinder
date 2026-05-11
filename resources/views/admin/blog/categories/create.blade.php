<x-admin-layout>
    <x-slot name="header">New Category</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.blog-categories.index') }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">&larr; Back to Categories</a>
    </div>

    <form method="POST" action="{{ route('admin.blog-categories.store') }}">
        @csrf

        <div class="max-w-2xl">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Category Details</h3>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors"
                               required>
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors"
                                  placeholder="Optional category description...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Create Category
                    </button>
                </div>
            </div>
        </div>
    </form>
</x-admin-layout>
