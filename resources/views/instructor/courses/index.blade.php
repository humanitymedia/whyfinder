<x-instructor-layout>
    <x-slot name="header">My Courses</x-slot>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-brand-gray">{{ $courses->total() }} {{ Str::plural('course', $courses->total()) }}</p>
        <a href="{{ route('instructor.courses.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Course
        </a>
    </div>

    @if($courses->count() > 0)
        <div class="grid gap-4">
            @foreach($courses as $course)
                <div class="bg-white rounded-xl border border-gray-200 p-6 flex items-center gap-6 hover:shadow-sm transition-shadow">
                    {{-- Thumbnail placeholder --}}
                    <div class="w-24 h-16 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                        @if($course->thumbnail)
                            <img src="{{ Storage::url($course->thumbnail) }}" alt="" class="w-full h-full object-cover rounded-lg">
                        @else
                            <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v12a2.25 2.25 0 002.25 2.25z" /></svg>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-brand-dark truncate">{{ $course->title }}</h3>
                        <p class="text-sm text-brand-gray mt-0.5">{{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }} &middot; {{ ucfirst($course->difficulty_level) }}</p>
                    </div>

                    <span class="inline-flex text-xs px-2.5 py-1 rounded-full shrink-0 {{ $course->status_badge_color }}">
                        {{ ucfirst($course->status) }}
                    </span>

                    <div class="flex items-center gap-1 shrink-0">
                        <a href="{{ route('instructor.courses.edit', $course) }}"
                           class="p-2 text-brand-gray hover:text-brand-dark transition-colors rounded-lg hover:bg-gray-100" title="Edit">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                        </a>
                        <form method="POST" action="{{ route('instructor.courses.destroy', $course) }}"
                              onsubmit="return confirm('Delete this course? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-brand-gray hover:text-red-600 transition-colors rounded-lg hover:bg-gray-100" title="Delete">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($courses->hasPages())
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        @endif
    @else
        <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            </div>
            <h3 class="text-lg font-bold text-brand-dark mb-2">No courses yet</h3>
            <p class="text-brand-gray mb-6">Create your first course and start sharing your knowledge.</p>
            <a href="{{ route('instructor.courses.create') }}"
               class="inline-flex items-center px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
                Create Your First Course
            </a>
        </div>
    @endif
</x-instructor-layout>
