<x-public-layout>
    <x-slot name="title">WhyFinder — Discover Your Why. Build Your Life.</x-slot>

    {{-- Hero Section --}}
    <section class="relative bg-brand-brown overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-brand-brown/90 to-brand-brown/70"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="max-w-2xl">
                <p class="text-brand-red text-sm font-bold uppercase tracking-widest mb-4">Your Purpose Is Waiting</p>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    Discover Your Why.<br>Build Your Life.
                </h1>
                <p class="text-lg text-gray-300 mb-8 leading-relaxed">
                    Free courses to help you find your purpose. Paid courses to help you build a business and life around it. Start your journey today.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-8 py-3 bg-brand-red text-white font-semibold rounded-full hover:bg-red-700 transition-colors text-sm">
                        Start Free Course
                    </a>
                    <a href="{{ route('courses.index') }}"
                       class="inline-flex items-center px-8 py-3 bg-white/10 text-white font-semibold rounded-full hover:bg-white/20 transition-colors border border-white/20 text-sm">
                        Browse Courses
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-brand-dark mb-4">How It Works</h2>
                <p class="text-brand-gray max-w-2xl mx-auto">Three simple steps to discovering your purpose and building a life around it.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-red-50 text-brand-red rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Find Your Why</h3>
                    <p class="text-brand-gray leading-relaxed">Take our free course to uncover your passions, strengths, and the purpose that drives you.</p>
                </div>
                {{-- Step 2 --}}
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-red-50 text-brand-red rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Build Your Skills</h3>
                    <p class="text-brand-gray leading-relaxed">Learn practical skills like website building, branding, marketing, and entrepreneurship from real instructors.</p>
                </div>
                {{-- Step 3 --}}
                <div class="text-center p-8">
                    <div class="w-16 h-16 bg-red-50 text-brand-red rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Live Your Purpose</h3>
                    <p class="text-brand-gray leading-relaxed">Apply what you learn to build a business and life that aligns with who you truly are.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Courses --}}
    <section class="py-20 bg-brand-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-brand-dark mb-2">Start with purpose. Build with skill.</h2>
                    <p class="text-brand-gray">Courses built by people who have done the work themselves.</p>
                </div>
                <a href="{{ route('courses.index') }}" class="hidden sm:inline-flex text-sm font-medium text-brand-red hover:text-red-700 transition-colors">
                    View all courses &rarr;
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse($featuredCourses as $course)
                    <a href="{{ route('courses.show', $course->slug) }}" class="bg-white rounded-xl border border-brand-gray-light overflow-hidden hover:shadow-lg transition-shadow group">
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
                            <div class="flex items-center gap-2 mb-3">
                                @if($course->category)
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-red-50 text-brand-red border border-red-100">{{ $course->category->name }}</span>
                                @endif
                                @if($course->difficulty_level)
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-gray-50 text-brand-gray border border-gray-200">{{ ucfirst($course->difficulty_level) }}</span>
                                @endif
                            </div>
                            <h3 class="font-bold text-brand-dark mb-2 group-hover:text-brand-red transition-colors">{{ $course->title }}</h3>
                            <p class="text-sm text-brand-gray mb-4 line-clamp-2">{{ $course->short_description }}</p>
                            @if($course->average_rating)
                                <div class="flex items-center gap-1 mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($course->average_rating) ? 'text-amber-500' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                    <span class="text-xs text-brand-gray ml-1">{{ $course->average_rating }} ({{ $course->review_count }})</span>
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                @if($course->is_free)
                                    <span class="text-sm font-bold text-green-600">Free</span>
                                @else
                                    <span class="text-sm font-bold text-brand-dark">${{ number_format($course->price, 2) }}</span>
                                @endif
                                <span class="text-xs text-brand-gray">by {{ $course->instructor->name ?? 'Unknown' }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    {{-- Placeholder cards when no courses exist yet --}}
                    @foreach(['Find Your Why', 'Build Your Website', 'Brand Yourself'] as $placeholder)
                        <div class="bg-white rounded-xl border border-brand-gray-light overflow-hidden">
                            <div class="h-48 bg-brand-blush flex items-center justify-center">
                                <span class="text-brand-gray text-sm font-medium">{{ $placeholder }}</span>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-brand-dark mb-2">{{ $placeholder }}</h3>
                                <p class="text-sm text-brand-gray mb-4">Courses coming soon. Check back for updates.</p>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>

            <div class="text-center mt-8 sm:hidden">
                <a href="{{ route('courses.index') }}" class="text-sm font-medium text-brand-red hover:text-red-700 transition-colors">View all courses &rarr;</a>
            </div>
        </div>
    </section>

    {{-- Testimonials / Stories of Purpose --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-brand-dark mb-4">Stories of Purpose</h2>
                <p class="text-brand-gray max-w-2xl mx-auto">Real people who discovered their why and built a life around it.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-brand-cream rounded-xl p-8 border border-brand-gray-light">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-brand-gray mb-6 leading-relaxed">"The Find Your Why course changed everything for me. I went from feeling lost in my career to launching a coaching business I'm proud of."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-blush flex items-center justify-center text-sm font-bold text-brand-brown">JR</div>
                        <div>
                            <p class="text-sm font-semibold text-brand-dark">Jessica Rodriguez</p>
                            <p class="text-xs text-brand-gray">Life Coach, Austin TX</p>
                        </div>
                    </div>
                </div>

                <div class="bg-brand-cream rounded-xl p-8 border border-brand-gray-light">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-brand-gray mb-6 leading-relaxed">"I built my entire website using the skills I learned here — for under $500. Now I'm teaching others to do the same."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-blush flex items-center justify-center text-sm font-bold text-brand-brown">MT</div>
                        <div>
                            <p class="text-sm font-semibold text-brand-dark">Marcus Thompson</p>
                            <p class="text-xs text-brand-gray">Freelance Designer, Portland OR</p>
                        </div>
                    </div>
                </div>

                <div class="bg-brand-cream rounded-xl p-8 border border-brand-gray-light">
                    <div class="flex items-center gap-1 mb-4">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="text-brand-gray mb-6 leading-relaxed">"I quit my six-figure job to follow my why. Best decision I ever made. This platform gave me the roadmap."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-blush flex items-center justify-center text-sm font-bold text-brand-brown">AK</div>
                        <div>
                            <p class="text-sm font-semibold text-brand-dark">Anika Kapoor</p>
                            <p class="text-xs text-brand-gray">Entrepreneur, Brooklyn NY</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Bottom CTA --}}
    <section class="py-20 bg-brand-brown">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to Find Your Why?</h2>
            <p class="text-gray-300 mb-8 text-lg">Start with our free course and discover the purpose that's been waiting for you all along.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}"
                   class="inline-flex items-center px-8 py-3 bg-brand-red text-white font-semibold rounded-full hover:bg-red-700 transition-colors text-sm">
                    Get Started Free
                </a>
                <a href="{{ route('teach') }}"
                   class="inline-flex items-center px-8 py-3 bg-white/10 text-white font-semibold rounded-full hover:bg-white/20 transition-colors border border-white/20 text-sm">
                    Become an Instructor
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
