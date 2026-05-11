<x-public-layout>
    <x-slot name="title">{{ $course->title }} — WhyFinder</x-slot>

    {{-- Course Header --}}
    <section class="bg-brand-brown py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-sm mb-6">
                    <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-white transition-colors">Courses</a>
                    <span class="text-gray-500">/</span>
                    @if($course->category)
                        <a href="{{ route('courses.index', ['category' => $course->category->slug]) }}" class="text-gray-400 hover:text-white transition-colors">{{ $course->category->name }}</a>
                        <span class="text-gray-500">/</span>
                    @endif
                    <span class="text-gray-300">{{ $course->title }}</span>
                </nav>

                @if($course->category)
                    <span class="inline-flex text-xs font-medium px-2.5 py-0.5 rounded-full bg-white/10 text-white border border-white/20 mb-4">{{ $course->category->name }}</span>
                @endif
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $course->title }}</h1>
                <p class="text-lg text-gray-300 mb-6">{{ $course->short_description }}</p>

                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-300">
                    {{-- Rating --}}
                    @if($course->average_rating)
                        <div class="flex items-center gap-1">
                            <span class="font-bold text-amber-400">{{ $course->average_rating }}</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($course->average_rating) ? 'text-amber-400' : 'text-gray-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span>({{ $course->review_count }} {{ Str::plural('review', $course->review_count) }})</span>
                        </div>
                    @endif

                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{ $course->enrollments_count }} {{ Str::plural('student', $course->enrollments_count) }}
                    </span>

                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}
                    </span>

                    @if($course->difficulty_level)
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            {{ ucfirst($course->difficulty_level) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-12 bg-brand-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                {{-- Left Column: Course Details --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Flash messages --}}
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
                    @endif

                    {{-- Description --}}
                    <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8">
                        <h2 class="text-xl font-bold text-brand-dark mb-4">About This Course</h2>
                        <div class="prose prose-sm max-w-none text-brand-gray">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>

                    {{-- Curriculum --}}
                    <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-brand-dark">Course Curriculum</h2>
                            <span class="text-sm text-brand-gray">{{ $course->sections->count() }} {{ Str::plural('section', $course->sections->count()) }} &middot; {{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}</span>
                        </div>

                        <div x-data="{ openSection: 0 }" class="space-y-3">
                            @foreach($course->sections as $index => $section)
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <button @click="openSection = openSection === {{ $index }} ? -1 : {{ $index }}"
                                            class="w-full flex items-center justify-between px-5 py-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                                        <div>
                                            <h3 class="font-semibold text-brand-dark text-sm">{{ $section->title }}</h3>
                                            <p class="text-xs text-brand-gray mt-0.5">{{ $section->lessons->count() }} {{ Str::plural('lesson', $section->lessons->count()) }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-brand-gray transition-transform" :class="openSection === {{ $index }} && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div x-show="openSection === {{ $index }}" x-cloak x-collapse>
                                        <div class="divide-y divide-gray-100">
                                            @foreach($section->lessons as $lesson)
                                                <div class="flex items-center gap-3 px-5 py-3">
                                                    {{-- Icon by type --}}
                                                    @if($lesson->type === 'video')
                                                        <svg class="w-4 h-4 text-brand-gray flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-brand-gray flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @endif

                                                    <span class="text-sm text-brand-dark flex-1">{{ $lesson->title }}</span>

                                                    @if($lesson->is_free_preview)
                                                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Preview</span>
                                                    @endif

                                                    @if($lesson->video_duration)
                                                        <span class="text-xs text-brand-gray">{{ $lesson->formatted_duration }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Instructor --}}
                    @if($course->instructor)
                        <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8">
                            <h2 class="text-xl font-bold text-brand-dark mb-4">Your Instructor</h2>
                            <div class="flex items-start gap-4">
                                <div class="w-16 h-16 rounded-full bg-brand-blush flex items-center justify-center text-lg font-bold text-brand-brown flex-shrink-0">
                                    {{ strtoupper(substr($course->instructor->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-brand-dark">{{ $course->instructor->name }}</h3>
                                    <p class="text-sm text-brand-gray mt-1">Instructor on WhyFinder</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Discussion Forum --}}
                    @auth
                        @if($isEnrolled || (auth()->user()->hasRole('instructor') && $course->instructor_id === auth()->id()) || auth()->user()->hasRole(['admin', 'manager']))
                            <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl font-bold text-brand-dark mb-1">Discussion Forum</h2>
                                        <p class="text-sm text-brand-gray">Ask questions, share insights, and connect with fellow learners.</p>
                                    </div>
                                    <a href="{{ route('forum.index', $course->slug) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-brand-brown text-white text-sm font-medium rounded-lg hover:bg-brand-brown/90 transition-colors flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" /></svg>
                                        Visit Forum
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endauth

                    {{-- Certificate Download --}}
                    @auth
                        @if(isset($userCertificate) && $userCertificate)
                            <div class="bg-green-50 rounded-xl border border-green-200 p-6 md:p-8">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="font-bold text-green-800">Course Completed!</h2>
                                            <p class="text-sm text-green-600">Your certificate is ready to download.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('certificates.download', $userCertificate) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download Certificate
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endauth

                    {{-- Review Form --}}
                    @auth
                        @if($isEnrolled && !$hasReviewed)
                            <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8" x-data="{ rating: 0, hoverRating: 0 }">
                                <h2 class="text-xl font-bold text-brand-dark mb-4">Leave a Review</h2>
                                <form method="POST" action="{{ route('reviews.store', $course->slug) }}">
                                    @csrf
                                    <input type="hidden" name="rating" :value="rating">

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-brand-gray mb-2">Your Rating</label>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button"
                                                        @click="rating = {{ $i }}"
                                                        @mouseenter="hoverRating = {{ $i }}"
                                                        @mouseleave="hoverRating = 0"
                                                        class="focus:outline-none">
                                                    <svg class="w-8 h-8 transition-colors cursor-pointer"
                                                         :class="(hoverRating || rating) >= {{ $i }} ? 'text-amber-500' : 'text-gray-200'"
                                                         fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </button>
                                            @endfor
                                            <span class="text-sm text-brand-gray ml-2" x-show="rating > 0" x-text="rating + '/5'"></span>
                                        </div>
                                        @error('rating') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-brand-gray mb-1">Comment (optional)</label>
                                        <textarea name="comment" rows="3"
                                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm"
                                                  placeholder="Tell others about your experience...">{{ old('comment') }}</textarea>
                                        @error('comment') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>

                                    <button type="submit"
                                            class="px-5 py-2.5 bg-brand-red text-white font-semibold text-sm rounded-lg hover:bg-red-700 transition-colors"
                                            :disabled="rating === 0"
                                            :class="rating === 0 && 'opacity-50 cursor-not-allowed'">
                                        Submit Review
                                    </button>
                                </form>
                            </div>
                        @elseif($isEnrolled && $hasReviewed)
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                                <svg class="w-8 h-8 text-green-500 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm text-brand-gray">You've already reviewed this course. Thank you!</p>
                            </div>
                        @endif
                    @endauth

                    {{-- Reviews --}}
                    @if($course->approvedReviews->count())
                        <div class="bg-white rounded-xl border border-brand-gray-light p-6 md:p-8">
                            <h2 class="text-xl font-bold text-brand-dark mb-6">Student Reviews</h2>
                            <div class="space-y-6">
                                @foreach($course->approvedReviews as $review)
                                    <div class="flex items-start gap-4 {{ !$loop->last ? 'pb-6 border-b border-gray-100' : '' }}">
                                        <div class="w-10 h-10 rounded-full bg-brand-blush flex items-center justify-center text-sm font-bold text-brand-brown flex-shrink-0">
                                            {{ strtoupper(substr($review->user->name ?? 'U', 0, 2)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-semibold text-sm text-brand-dark">{{ $review->user->name ?? 'Student' }}</span>
                                                <span class="text-xs text-brand-gray">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="flex items-center gap-0.5 mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-500' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                            @if($review->comment)
                                                <p class="text-sm text-brand-gray">{{ $review->comment }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right Column: Enrollment Card --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-brand-gray-light p-6 sticky top-36">
                        {{-- Thumbnail --}}
                        <div class="h-44 bg-brand-blush rounded-lg flex items-center justify-center mb-6 overflow-hidden">
                            @if($course->thumbnail)
                                <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-12 h-12 text-brand-gray/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>

                        {{-- Price --}}
                        <div class="mb-6">
                            @if($course->is_free)
                                <span class="text-3xl font-bold text-green-600">Free</span>
                            @else
                                <span class="text-3xl font-bold text-brand-dark">${{ number_format($course->price, 2) }}</span>
                            @endif
                        </div>

                        {{-- Enroll / Continue Button --}}
                        @auth
                            @if($isEnrolled)
                                @php
                                    $firstLesson = $course->lessons()->orderBy('course_sections.sort_order')->orderBy('lessons.sort_order')->first();
                                @endphp
                                @if($firstLesson)
                                    <a href="{{ route('learn.show', [$course->slug, $firstLesson->id]) }}"
                                       class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-brand-brown text-white font-semibold rounded-lg hover:bg-brand-brown/90 transition-colors text-sm">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Continue Learning
                                    </a>
                                @endif
                            @else
                                @if($course->is_free || $course->price == 0)
                                    <form method="POST" action="{{ route('enroll', $course) }}">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-brand-red text-white font-semibold rounded-lg hover:bg-red-700 transition-colors text-sm">
                                            Enroll for Free
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('enroll', $course) }}">
                                        @csrf
                                        <button type="submit" class="w-full px-6 py-3 bg-brand-red text-white font-semibold rounded-lg hover:bg-red-700 transition-colors text-sm flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                            </svg>
                                            Purchase Course
                                        </button>
                                    </form>
                                    <p class="text-xs text-brand-gray text-center mt-2">Secure payment via Stripe</p>
                                @endif
                            @endif
                        @else
                            <a href="{{ route('register') }}"
                               class="w-full flex items-center justify-center px-6 py-3 bg-brand-red text-white font-semibold rounded-lg hover:bg-red-700 transition-colors text-sm">
                                Sign Up to Enroll
                            </a>
                            <p class="text-xs text-brand-gray text-center mt-2">
                                Already have an account? <a href="{{ route('login') }}" class="text-brand-red hover:underline">Log in</a>
                            </p>
                        @endauth

                        {{-- Course Info List --}}
                        <div class="mt-6 pt-6 border-t border-gray-100 space-y-3">
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-brand-gray flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="text-brand-gray">{{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}</span>
                            </div>
                            @if($course->difficulty_level)
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-5 h-5 text-brand-gray flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span class="text-brand-gray">{{ ucfirst($course->difficulty_level) }} level</span>
                                </div>
                            @endif
                            <div class="flex items-center gap-3 text-sm">
                                <svg class="w-5 h-5 text-brand-gray flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-brand-gray">{{ $course->enrollments_count }} {{ Str::plural('student', $course->enrollments_count) }} enrolled</span>
                            </div>
                            @if($course->duration_hours)
                                <div class="flex items-center gap-3 text-sm">
                                    <svg class="w-5 h-5 text-brand-gray flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-brand-gray">{{ $course->duration_hours }} {{ Str::plural('hour', $course->duration_hours) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-layout>
