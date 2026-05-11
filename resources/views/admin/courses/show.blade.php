<x-admin-layout>
    <x-slot name="header">Course: {{ $course->title }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.courses.index') }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">&larr; Back to Courses</a>
    </div>

    {{-- Course info --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-lg bg-brand-blush flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" alt="" class="w-full h-full object-cover">
                @else
                    <svg class="w-8 h-8 text-brand-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-lg font-bold text-brand-dark">{{ $course->title }}</h2>
                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $course->status_badge_color }}">{{ ucfirst($course->status) }}</span>
                </div>
                <p class="text-sm text-brand-gray">
                    By {{ $course->instructor->name ?? 'Unknown' }}
                    @if($course->category)
                        &middot; {{ $course->category->name }}
                    @endif
                    &middot;
                    @if($course->is_free)
                        Free
                    @else
                        ${{ number_format($course->price, 2) }}
                    @endif
                    &middot; Created {{ $course->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Students</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ number_format($enrollmentCount) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Revenue</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">${{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Avg Rating</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ $avgRating ? number_format($avgRating, 1) : '—' }}
                @if($reviewCount)
                    <span class="text-sm font-normal text-brand-gray">({{ $reviewCount }})</span>
                @endif
            </p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Lessons</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ $totalLessons }}</p>
        </div>
    </div>

    {{-- Enrolled Students --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">Enrolled Students</h3>
        </div>

        @if($enrollments->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Student</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Email</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Enrolled</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Progress</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($enrollments as $enrollment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 flex-shrink-0">
                                            {{ strtoupper(substr($enrollment->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-brand-dark">{{ $enrollment->user->name ?? 'Deleted' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-brand-gray">{{ $enrollment->user->email ?? '—' }}</td>
                                <td class="px-6 py-4 text-brand-gray">{{ ($enrollment->enrolled_at ?? $enrollment->created_at)->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $enrollment->progress >= 100 ? 'bg-green-500' : 'bg-brand-red' }}"
                                                 style="width: {{ $enrollment->progress }}%"></div>
                                        </div>
                                        <span class="text-xs text-brand-gray whitespace-nowrap">{{ $enrollment->completed_lessons }}/{{ $enrollment->total_lessons }} ({{ $enrollment->progress }}%)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($enrollment->completed_at)
                                        <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Completed</span>
                                    @elseif($enrollment->progress > 0)
                                        <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">In Progress</span>
                                    @else
                                        <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Not Started</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($enrollments->hasPages())
                <div class="px-6 py-4">
                    {{ $enrollments->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No students enrolled in this course yet.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
