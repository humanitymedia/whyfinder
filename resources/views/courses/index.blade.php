<x-public-layout>
    <x-slot name="title">Courses — WhyFinder</x-slot>

    {{-- Hero / Header --}}
    <section class="bg-brand-brown py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Explore Courses</h1>
            <p class="text-gray-300 text-lg max-w-2xl">Find your purpose and build the skills to live it. Free and premium courses from real instructors.</p>
        </div>
    </section>

    {{-- Filters & Search --}}
    <section class="bg-white border-b border-brand-gray-light sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form method="GET" action="{{ route('courses.index') }}" class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                {{-- Search --}}
                <div class="relative flex-1 w-full">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-brand-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-brand-red focus:border-brand-red">
                </div>

                {{-- Level filter --}}
                <select name="level" onchange="this.form.submit()"
                        class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-brand-red focus:border-brand-red">
                    <option value="">All Levels</option>
                    @foreach(['beginner', 'intermediate', 'advanced'] as $lvl)
                        <option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    Search
                </button>

                @if(request('search') || request('category') || request('level'))
                    <a href="{{ route('courses.index') }}" class="text-sm text-brand-gray hover:text-brand-red transition-colors">Clear</a>
                @endif
            </form>

            {{-- Category pills --}}
            @if($categories->count())
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('courses.index', array_filter(request()->except('category', 'page'))) }}"
                       class="text-xs font-medium px-3 py-1.5 rounded-full border transition-colors {{ !request('category') ? 'bg-brand-brown text-white border-brand-brown' : 'bg-white text-brand-dark border-gray-200 hover:border-brand-brown' }}">
                        All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('courses.index', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                           class="text-xs font-medium px-3 py-1.5 rounded-full border transition-colors {{ request('category') === $category->slug ? 'bg-brand-brown text-white border-brand-brown' : 'bg-white text-brand-dark border-gray-200 hover:border-brand-brown' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Course Grid --}}
    <section class="py-12 bg-brand-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($courses->count())
                <p class="text-sm text-brand-gray mb-6">{{ $courses->total() }} {{ Str::plural('course', $courses->total()) }} found</p>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($courses as $course)
                        <a href="{{ route('courses.show', $course->slug) }}"
                           class="bg-white rounded-xl border border-brand-gray-light overflow-hidden hover:shadow-lg transition-shadow group">
                            {{-- Thumbnail --}}
                            <div class="h-48 bg-brand-blush flex items-center justify-center relative">
                                @if($course->thumbnail)
                                    <img src="{{ Storage::url($course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-brand-gray text-sm font-medium">{{ $course->title }}</span>
                                @endif
                                @if($course->is_free)
                                    <span class="absolute top-3 left-3 text-xs font-bold px-2.5 py-1 rounded-full bg-green-500 text-white">Free</span>
                                @endif
                            </div>
                            <div class="p-6">
                                {{-- Category & Level --}}
                                <div class="flex items-center gap-2 mb-3">
                                    @if($course->category)
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-red-50 text-brand-red border border-red-100">{{ $course->category->name }}</span>
                                    @endif
                                    @if($course->difficulty_level)
                                        <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-50 text-brand-gray border border-gray-200">{{ ucfirst($course->difficulty_level) }}</span>
                                    @endif
                                </div>

                                {{-- Title --}}
                                <h3 class="font-bold text-brand-dark mb-2 group-hover:text-brand-red transition-colors">{{ $course->title }}</h3>

                                {{-- Description --}}
                                <p class="text-sm text-brand-gray mb-4 line-clamp-2">{{ $course->short_description }}</p>

                                {{-- Rating --}}
                                @if($course->average_rating)
                                    <div class="flex items-center gap-1 mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= round($course->average_rating) ? 'text-amber-500' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-xs text-brand-gray ml-1">{{ $course->average_rating }} ({{ $course->review_count }})</span>
                                    </div>
                                @endif

                                {{-- Price & Instructor --}}
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                    @if($course->is_free)
                                        <span class="text-sm font-bold text-green-600">Free</span>
                                    @else
                                        <span class="text-sm font-bold text-brand-dark">${{ number_format($course->price, 2) }}</span>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-brand-gray">{{ $course->lessons_count }} {{ Str::plural('lesson', $course->lessons_count) }}</span>
                                        <span class="text-xs text-brand-gray">&middot;</span>
                                        <span class="text-xs text-brand-gray">{{ $course->instructor->name ?? 'Unknown' }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-brand-gray/30 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="text-xl font-bold text-brand-dark mb-2">No courses found</h3>
                    <p class="text-brand-gray mb-6">Try adjusting your search or filters.</p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
                        View All Courses
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
