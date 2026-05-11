<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $lesson->title }} — {{ $course->title }} — WhyFinder</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-cream text-brand-dark" x-data="{ sidebarOpen: true }">
        {{-- Top Bar --}}
        <header class="bg-brand-brown text-white h-14 flex items-center px-4 sticky top-0 z-50">
            <div class="flex items-center gap-4 flex-1">
                {{-- Back to course --}}
                <a href="{{ route('courses.show', $course->slug) }}" class="flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="hidden sm:inline">{{ Str::limit($course->title, 30) }}</span>
                </a>
            </div>

            {{-- Progress --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2">
                    <div class="w-32 h-2 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full bg-green-400 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <span class="text-xs text-gray-300">{{ $completedCount }}/{{ $totalLessons }}</span>
                </div>

                {{-- Toggle sidebar --}}
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-300 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </header>

        <div class="flex" style="height: calc(100vh - 3.5rem)">
            {{-- Sidebar --}}
            <aside x-show="sidebarOpen" x-cloak
                   class="w-80 bg-white border-r border-brand-gray-light overflow-y-auto flex-shrink-0"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-150"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full">
                <div class="p-4">
                    <h2 class="font-bold text-brand-dark text-sm mb-1">{{ $course->title }}</h2>
                    <p class="text-xs text-brand-gray mb-4">{{ $progressPercent }}% complete</p>
                    <div class="w-full h-1.5 bg-gray-100 rounded-full mb-6">
                        <div class="h-full bg-green-500 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                    </div>
                </div>

                <nav class="pb-6">
                    @foreach($course->sections as $section)
                        <div class="mb-1">
                            <div class="px-4 py-2 bg-gray-50">
                                <h3 class="text-xs font-bold text-brand-gray uppercase tracking-wider">{{ $section->title }}</h3>
                            </div>
                            <div class="divide-y divide-gray-50">
                                @foreach($section->lessons as $sidebarLesson)
                                    @php
                                        $isActive = $sidebarLesson->id === $lesson->id;
                                        $isCompleted = $completedLessonIds->contains($sidebarLesson->id);
                                    @endphp
                                    <a href="{{ route('learn.show', [$course->slug, $sidebarLesson->id]) }}"
                                       class="flex items-center gap-3 px-4 py-3 text-sm transition-colors {{ $isActive ? 'bg-red-50 text-brand-red border-l-2 border-brand-red' : 'text-brand-dark hover:bg-gray-50' }}">
                                        {{-- Status icon --}}
                                        @if($isCompleted)
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @elseif($isActive)
                                            <div class="w-5 h-5 rounded-full border-2 border-brand-red flex items-center justify-center flex-shrink-0">
                                                <div class="w-2 h-2 bg-brand-red rounded-full"></div>
                                            </div>
                                        @else
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex-shrink-0"></div>
                                        @endif
                                        <span class="truncate">{{ $sidebarLesson->title }}</span>
                                        @if($sidebarLesson->video_duration)
                                            <span class="text-xs text-brand-gray ml-auto flex-shrink-0">{{ $sidebarLesson->formatted_duration }}</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 overflow-y-auto">
                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="m-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                @endif

                <div class="max-w-4xl mx-auto px-6 py-8">
                    {{-- Lesson Title --}}
                    <h1 class="text-2xl font-bold text-brand-dark mb-6">{{ $lesson->title }}</h1>

                    {{-- Video --}}
                    @if($lesson->type === 'video' && $lesson->video_url)
                        <div class="mb-8 rounded-xl overflow-hidden bg-black aspect-video">
                            @if(str_contains($lesson->video_url, 'youtube') || str_contains($lesson->video_url, 'youtu.be'))
                                @php
                                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]+)/', $lesson->video_url, $matches);
                                    $youtubeId = $matches[1] ?? '';
                                @endphp
                                @if($youtubeId)
                                    <iframe src="https://www.youtube.com/embed/{{ $youtubeId }}" class="w-full h-full" allowfullscreen></iframe>
                                @endif
                            @elseif(str_contains($lesson->video_url, 'vimeo'))
                                @php
                                    preg_match('/vimeo\.com\/(\d+)/', $lesson->video_url, $matches);
                                    $vimeoId = $matches[1] ?? '';
                                @endphp
                                @if($vimeoId)
                                    <iframe src="https://player.vimeo.com/video/{{ $vimeoId }}" class="w-full h-full" allowfullscreen></iframe>
                                @endif
                            @else
                                <video src="{{ $lesson->video_url }}" controls class="w-full h-full"></video>
                            @endif
                        </div>
                    @endif

                    {{-- Text Content --}}
                    @if($lesson->content)
                        <div class="prose prose-sm max-w-none text-brand-gray mb-8">
                            {!! nl2br(e($lesson->content)) !!}
                        </div>
                    @endif

                    {{-- Downloadable File --}}
                    @if($lesson->downloadable_file_path)
                        <div class="mb-8 p-4 bg-gray-50 border border-gray-200 rounded-lg flex items-center gap-3">
                            <svg class="w-8 h-8 text-brand-gray" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-brand-dark">Downloadable Resource</p>
                                <p class="text-xs text-brand-gray">{{ basename($lesson->downloadable_file_path) }}</p>
                            </div>
                            <a href="{{ Storage::url($lesson->downloadable_file_path) }}" download
                               class="px-4 py-2 bg-white border border-gray-200 text-sm font-medium text-brand-dark rounded-lg hover:bg-gray-50 transition-colors">
                                Download
                            </a>
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        {{-- Previous --}}
                        <div>
                            @if($prevLesson)
                                <a href="{{ route('learn.show', [$course->slug, $prevLesson->id]) }}"
                                   class="inline-flex items-center gap-2 text-sm font-medium text-brand-gray hover:text-brand-dark transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Previous
                                </a>
                            @endif
                        </div>

                        {{-- Mark Complete / Next --}}
                        <div class="flex items-center gap-3">
                            @if($currentProgress && $currentProgress->is_completed)
                                <span class="inline-flex items-center gap-1 text-sm font-medium text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    Completed
                                </span>
                            @else
                                <form method="POST" action="{{ route('learn.complete', [$course->slug, $lesson->id]) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Mark as Complete
                                    </button>
                                </form>
                            @endif

                            @if($nextLesson)
                                <a href="{{ route('learn.show', [$course->slug, $nextLesson->id]) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-brown text-white text-sm font-semibold rounded-lg hover:bg-brand-brown/90 transition-colors">
                                    Next
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
