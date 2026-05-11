<x-instructor-layout>
    <x-slot name="header">Instructor Dashboard</x-slot>

    {{-- Welcome message --}}
    <p class="text-brand-gray mb-6">Welcome back, {{ auth()->user()->name }}.</p>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Courses --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">{{ $stats['total_courses'] }}</p>
            <p class="text-sm text-brand-gray mt-1">Courses</p>
        </div>

        {{-- Total Students --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">{{ $stats['total_students'] }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Students</p>
        </div>

        {{-- Total Earnings --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['total_earnings'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Earnings</p>
        </div>

        {{-- This Month --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['monthly_earnings'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">This Month</p>
        </div>
    </div>

    {{-- Your Courses --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-brand-dark">Your Courses</h3>
            <a href="{{ route('instructor.courses.create') }}" class="text-sm text-brand-red hover:text-red-700 transition-colors">+ New Course</a>
        </div>

        @if($courses->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Students</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Revenue</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Lessons</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($courses as $course)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-brand-dark">{{ $course->title }}</span>
                                </td>
                                <td class="px-6 py-4 text-brand-gray">{{ $course->enrollments_count }}</td>
                                <td class="px-6 py-4 text-brand-gray">${{ number_format($course->instructorEarnings()->sum('net_amount'), 2) }}</td>
                                <td class="px-6 py-4 text-brand-gray">{{ $course->lessons_count }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $course->status_badge_color }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('instructor.courses.edit', $course) }}"
                                           class="p-1.5 text-brand-gray hover:text-brand-dark transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>You haven't created any courses yet.</p>
            </div>
        @endif
    </div>

    {{-- Course Builder CTA --}}
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand-red" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        </div>
        <h3 class="text-xl font-bold text-brand-dark mb-2">Create Your Next Course</h3>
        <p class="text-brand-gray mb-6 max-w-md mx-auto">Share your knowledge with purpose-driven learners. Build engaging courses with our simple course builder.</p>
        <a href="{{ route('instructor.courses.create') }}"
           class="inline-flex items-center px-8 py-3 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
            Start Building
        </a>
    </div>
</x-instructor-layout>
