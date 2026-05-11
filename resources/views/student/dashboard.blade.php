<x-public-layout>
    <x-slot name="title">My Learning — WhyFinder</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">My Learning</h1>
                    <p class="text-gray-300">Track your progress and continue where you left off.</p>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <a href="{{ route('certificates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 text-white text-sm font-medium rounded-lg hover:bg-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        My Certificates
                    </a>
                    <a href="{{ route('payment.history') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 text-white text-sm font-medium rounded-lg hover:bg-white/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                        Payment History
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 bg-brand-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Stats Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                <div class="bg-white rounded-xl border border-brand-gray-light p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ $stats['enrolled_courses'] }}</p>
                        <p class="text-sm text-brand-gray">Enrolled Courses</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-brand-gray-light p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ $stats['in_progress'] }}</p>
                        <p class="text-sm text-brand-gray">In Progress</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-brand-gray-light p-5 flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-brand-dark">{{ $stats['completed_courses'] }}</p>
                        <p class="text-sm text-brand-gray">Completed</p>
                    </div>
                </div>
            </div>

            {{-- Enrolled Courses --}}
            @if($coursesWithProgress->count())
                <div class="space-y-4">
                    @foreach($coursesWithProgress as $item)
                        <div class="bg-white rounded-xl border border-brand-gray-light overflow-hidden hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row">
                                {{-- Thumbnail --}}
                                <div class="sm:w-48 h-32 sm:h-auto bg-brand-blush flex items-center justify-center flex-shrink-0">
                                    @if($item->course->thumbnail)
                                        <img src="{{ Storage::url($item->course->thumbnail) }}" alt="{{ $item->course->title }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-brand-gray text-xs font-medium">{{ Str::limit($item->course->title, 20) }}</span>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                @if($item->course->category)
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-50 text-brand-red border border-red-100">{{ $item->course->category->name }}</span>
                                                @endif
                                                @if($item->completed_at)
                                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-50 text-green-700 border border-green-100">Completed</span>
                                                @endif
                                            </div>
                                            <h3 class="font-bold text-brand-dark mb-1">{{ $item->course->title }}</h3>
                                            <p class="text-xs text-brand-gray">
                                                By {{ $item->course->instructor->name ?? 'Unknown' }}
                                                &middot; Enrolled {{ $item->enrolled_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        {{-- Continue button --}}
                                        @php
                                            $firstLesson = $item->course->lessons()->orderBy('course_sections.sort_order')->orderBy('lessons.sort_order')->first();
                                        @endphp
                                        @if($firstLesson)
                                            <a href="{{ route('learn.show', [$item->course->slug, $firstLesson->id]) }}"
                                               class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-brand-red text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors flex-shrink-0">
                                                {{ $item->completed_at ? 'Review' : 'Continue' }}
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>

                                    {{-- Progress Bar --}}
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between text-xs text-brand-gray mb-1.5">
                                            <span>{{ $item->completed_lessons }}/{{ $item->total_lessons }} lessons</span>
                                            <span class="font-medium {{ $item->progress_percent === 100 ? 'text-green-600' : 'text-brand-dark' }}">{{ $item->progress_percent }}%</span>
                                        </div>
                                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full transition-all {{ $item->progress_percent === 100 ? 'bg-green-500' : 'bg-brand-red' }}"
                                                 style="width: {{ $item->progress_percent }}%"></div>
                                        </div>
                                    </div>

                                    {{-- Mobile continue button --}}
                                    @if($firstLesson)
                                        <a href="{{ route('learn.show', [$item->course->slug, $firstLesson->id]) }}"
                                           class="sm:hidden mt-4 w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                            {{ $item->completed_at ? 'Review Course' : 'Continue Learning' }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-brand-gray/30 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="text-xl font-bold text-brand-dark mb-2">No courses yet</h3>
                    <p class="text-brand-gray mb-6">Start your learning journey by enrolling in a course.</p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
                        Browse Courses
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
