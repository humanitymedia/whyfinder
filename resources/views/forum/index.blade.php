<x-public-layout>
    <x-slot name="title">Forum — {{ $course->title }}</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm mb-4">
                <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-white transition-colors">Courses</a>
                <span class="text-gray-500">/</span>
                <a href="{{ route('courses.show', $course->slug) }}" class="text-gray-400 hover:text-white transition-colors">{{ $course->title }}</a>
                <span class="text-gray-500">/</span>
                <span class="text-gray-300">Forum</span>
            </nav>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Discussion Forum</h1>
                    <p class="mt-2 text-gray-300">{{ $threads->total() }} {{ Str::plural('thread', $threads->total()) }}</p>
                </div>
                <a href="{{ route('forum.create', $course->slug) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-brand-brown font-semibold text-sm rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    New Thread
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        @if($threads->count())
            <div class="space-y-3">
                @foreach($threads as $thread)
                    <a href="{{ route('forum.show', [$course->slug, $thread->slug]) }}"
                       class="block bg-white rounded-xl border border-brand-gray-light p-5 hover:shadow-md transition-all group">
                        <div class="flex items-start gap-4">
                            {{-- Author avatar --}}
                            <div class="w-10 h-10 rounded-full bg-brand-blush flex items-center justify-center text-sm font-bold text-brand-brown flex-shrink-0">
                                {{ strtoupper(substr($thread->user->name ?? 'U', 0, 2)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h3 class="font-semibold text-brand-dark group-hover:text-brand-red transition-colors truncate">{{ $thread->title }}</h3>
                                    @if($thread->is_pinned)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.653 16.915l-.005-.003-.019-.01a20.759 20.759 0 01-1.162-.682 22.045 22.045 0 01-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 018-2.828A4.5 4.5 0 0118 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 01-3.744 2.582l-.019.01-.005.003h-.002a.723.723 0 01-.692 0h-.002z" /></svg>
                                            Pinned
                                        </span>
                                    @endif
                                    @if($thread->is_locked)
                                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-700">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                            Locked
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3 text-xs text-brand-gray">
                                    <span>{{ $thread->user->name ?? 'Unknown' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $thread->created_at->diffForHumans() }}</span>
                                    @if($thread->lesson)
                                        <span>&middot;</span>
                                        <span class="text-brand-brown font-medium">{{ $thread->lesson->title }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- Reply count --}}
                            <div class="flex items-center gap-1.5 text-sm text-brand-gray flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                                {{ $thread->approved_replies_count }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $threads->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="w-16 h-16 text-brand-gray-light mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" /></svg>
                <h3 class="text-lg font-semibold text-brand-dark mb-2">No discussions yet</h3>
                <p class="text-brand-gray mb-6">Be the first to start a conversation in this course.</p>
                <a href="{{ route('forum.create', $course->slug) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-brown text-white font-semibold text-sm rounded-lg hover:bg-brand-brown/90 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Start a Discussion
                </a>
            </div>
        @endif
    </div>
</x-public-layout>
