<x-public-layout>
    <x-slot name="title">Edit Thread — {{ $course->title }}</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm mb-4">
                <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-white transition-colors">Courses</a>
                <span class="text-gray-500">/</span>
                <a href="{{ route('courses.show', $course->slug) }}" class="text-gray-400 hover:text-white transition-colors">{{ $course->title }}</a>
                <span class="text-gray-500">/</span>
                <a href="{{ route('forum.index', $course->slug) }}" class="text-gray-400 hover:text-white transition-colors">Forum</a>
                <span class="text-gray-500">/</span>
                <span class="text-gray-300">Edit Thread</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold">Edit Thread</h1>
        </div>
    </section>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <a href="{{ route('forum.show', [$course->slug, $thread->slug]) }}" class="inline-flex items-center gap-1 text-sm text-brand-gray hover:text-brand-dark transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to thread
        </a>

        <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8">
            <form method="POST" action="{{ route('forum.update', [$course->slug, $thread->slug]) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-medium text-brand-dark mb-1">Title <span class="text-brand-red">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $thread->title) }}" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($lessons->count())
                    <div>
                        <label for="lesson_id" class="block text-sm font-medium text-brand-dark mb-1">Related Lesson <span class="text-xs text-brand-gray">(optional)</span></label>
                        <select name="lesson_id" id="lesson_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                            <option value="">— General discussion —</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" {{ old('lesson_id', $thread->lesson_id) == $lesson->id ? 'selected' : '' }}>{{ $lesson->title }}</option>
                            @endforeach
                        </select>
                        @error('lesson_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <div>
                    <label for="body" class="block text-sm font-medium text-brand-dark mb-1">Body <span class="text-brand-red">*</span></label>
                    <textarea name="body" id="body" rows="8" required
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">{{ old('body', $thread->body) }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-6 py-2.5 bg-brand-brown text-white font-semibold text-sm rounded-lg hover:bg-brand-brown/90 transition-colors">
                        Update Thread
                    </button>
                    <a href="{{ route('forum.show', [$course->slug, $thread->slug]) }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-public-layout>
