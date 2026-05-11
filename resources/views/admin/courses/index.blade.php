<x-admin-layout>
    <x-slot name="header">Courses</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Total Courses</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ number_format($totalCourses) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Published</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($publishedCourses) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Total Students</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ number_format($totalStudents) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
            </div>
            <div>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>In Review</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div>
                <select name="instructor" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                    <option value="">All Instructors</option>
                    @foreach($instructors as $instructor)
                        <option value="{{ $instructor->id }}" {{ request('instructor') == $instructor->id ? 'selected' : '' }}>
                            {{ $instructor->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-brown text-white text-sm font-medium rounded-lg hover:bg-brand-brown/90 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status', 'instructor']))
                <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-sm text-brand-gray hover:text-brand-dark transition-colors text-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- Courses Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">All Courses</h3>
        </div>

        @if($courses->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Instructor</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Category</th>
                            <th class="text-center px-6 py-3 font-medium text-brand-gray">Students</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Revenue</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Created</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($courses as $course)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-brand-blush flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            @if($course->thumbnail)
                                                <img src="{{ Storage::url($course->thumbnail) }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-brand-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-brand-dark truncate max-w-[200px]">{{ $course->title }}</p>
                                            @if($course->is_free)
                                                <span class="text-xs text-green-600">Free</span>
                                            @else
                                                <span class="text-xs text-brand-gray">${{ number_format($course->price, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-brand-gray">{{ $course->instructor->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-brand-gray">{{ $course->category->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center text-brand-dark">{{ $course->enrollments_count }}</td>
                                <td class="px-6 py-4 text-right font-medium text-brand-dark">${{ number_format($course->total_revenue, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $course->status_badge_color }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-brand-gray">{{ $course->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end">
                                        <a href="{{ route('admin.courses.show', $course) }}"
                                           class="p-1.5 text-brand-gray hover:text-brand-dark transition-colors" title="View Details">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $courses->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No courses found.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
