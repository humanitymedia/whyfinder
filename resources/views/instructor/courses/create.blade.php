<x-instructor-layout>
    <x-slot name="header">Create New Course</x-slot>

    <div class="max-w-2xl">
        {{-- Back link --}}
        <a href="{{ route('instructor.courses.index') }}" class="inline-flex items-center gap-1 text-sm text-brand-gray hover:text-brand-dark transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Courses
        </a>

        {{-- Step indicator --}}
        <div class="flex items-center gap-3 mb-8">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-brand-red text-white text-sm font-bold flex items-center justify-center">1</span>
                <span class="text-sm font-medium text-brand-dark">Basic Info</span>
            </div>
            <div class="flex-1 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-gray-200 text-brand-gray text-sm font-bold flex items-center justify-center">2</span>
                <span class="text-sm text-brand-gray">Curriculum</span>
            </div>
            <div class="flex-1 h-px bg-gray-200"></div>
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-gray-200 text-brand-gray text-sm font-bold flex items-center justify-center">3</span>
                <span class="text-sm text-brand-gray">Publish</span>
            </div>
        </div>

        <form method="POST" action="{{ route('instructor.courses.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-semibold text-brand-dark">Course Details</h3>

                <div>
                    <label for="title" class="block text-sm font-medium text-brand-dark mb-1">Course Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title') }}" required
                           placeholder="e.g. Finding Your Life Purpose Through Journaling"
                           class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>

                <div>
                    <label for="short_description" class="block text-sm font-medium text-brand-dark mb-1">Short Description</label>
                    <input id="short_description" type="text" name="short_description" value="{{ old('short_description') }}"
                           placeholder="A brief one-liner for course cards"
                           class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                    <p class="text-xs text-brand-gray mt-1">Max 500 characters. Shown on course cards.</p>
                    <x-input-error :messages="$errors->get('short_description')" class="mt-1" />
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-brand-dark mb-1">Full Description</label>
                    <textarea id="description" name="description" rows="5"
                              placeholder="What will students learn? Who is this course for?"
                              class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1" />
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-semibold text-brand-dark">Category & Level</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-brand-dark mb-1">Category</label>
                        <select id="category_id" name="category_id"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                            <option value="">Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-1" />
                    </div>

                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-brand-dark mb-1">Difficulty Level</label>
                        <select id="difficulty_level" name="difficulty_level" required
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                            <option value="beginner" {{ old('difficulty_level', 'beginner') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('difficulty_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('difficulty_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        <x-input-error :messages="$errors->get('difficulty_level')" class="mt-1" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
                <h3 class="font-semibold text-brand-dark">Pricing</h3>

                <div x-data="{ isFree: {{ old('is_free') ? 'true' : 'false' }} }">
                    <label class="flex items-center gap-3 mb-4 cursor-pointer">
                        <input type="checkbox" name="is_free" value="1" x-model="isFree"
                               class="rounded border-gray-300 text-brand-red focus:ring-brand-red">
                        <span class="text-sm font-medium text-brand-dark">This is a free course</span>
                    </label>

                    <div x-show="!isFree" x-cloak>
                        <label for="price" class="block text-sm font-medium text-brand-dark mb-1">Price (USD)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand-gray text-sm">$</span>
                            <input id="price" type="number" name="price" value="{{ old('price', '0.00') }}" step="0.01" min="0"
                                   class="w-full rounded-lg border-gray-300 pl-8 pr-4 py-2.5 text-sm focus:border-brand-red focus:ring-brand-red">
                        </div>
                        <x-input-error :messages="$errors->get('price')" class="mt-1" />
                    </div>

                    <input x-show="isFree" type="hidden" name="price" value="0">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Create Course & Continue
                </button>
                <a href="{{ route('instructor.courses.index') }}" class="px-6 py-2.5 text-sm font-medium text-brand-gray border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-instructor-layout>
