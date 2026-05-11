<x-public-layout>
    <x-slot name="title">{{ $thread->title }} — Forum</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-sm mb-4">
                <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-white transition-colors">Courses</a>
                <span class="text-gray-500">/</span>
                <a href="{{ route('courses.show', $course->slug) }}" class="text-gray-400 hover:text-white transition-colors">{{ $course->title }}</a>
                <span class="text-gray-500">/</span>
                <a href="{{ route('forum.index', $course->slug) }}" class="text-gray-400 hover:text-white transition-colors">Forum</a>
                <span class="text-gray-500">/</span>
                <span class="text-gray-300 truncate max-w-xs">{{ $thread->title }}</span>
            </nav>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-2xl md:text-3xl font-bold">{{ $thread->title }}</h1>
                @if($thread->is_pinned)
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Pinned</span>
                @endif
                @if($thread->is_locked)
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-700">Locked</span>
                @endif
            </div>
            @if($thread->lesson)
                <p class="mt-2 text-sm text-gray-300">Related to: <span class="font-medium text-white">{{ $thread->lesson->title }}</span></p>
            @endif
        </div>
    </section>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
        @endif

        {{-- Original Post --}}
        <div class="bg-white rounded-xl border border-brand-gray-light p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-brand-blush flex items-center justify-center text-sm font-bold text-brand-brown flex-shrink-0">
                    {{ strtoupper(substr($thread->user->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-sm text-brand-dark">{{ $thread->user->name ?? 'Unknown' }}</span>
                        <span class="text-xs text-brand-gray">{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="prose prose-sm max-w-none text-brand-dark mt-3">
                        {!! nl2br(e($thread->body)) !!}
                    </div>

                    {{-- Thread actions --}}
                    <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                        @if($thread->user_id === auth()->id() || $canModerate)
                            <a href="{{ route('forum.edit', [$course->slug, $thread->slug]) }}" class="text-xs text-brand-gray hover:text-brand-dark transition-colors">Edit</a>
                        @endif
                        @if($thread->user_id === auth()->id() || $canModerate)
                            <form method="POST" action="{{ route('forum.destroy', [$course->slug, $thread->slug]) }}" class="inline" onsubmit="return confirm('Delete this thread?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Moderator Actions --}}
        @if($canModerate)
            <div class="flex items-center gap-2 mb-6">
                <form method="POST" action="{{ route('forum.togglePin', [$course->slug, $thread->slug]) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors {{ $thread->is_pinned ? 'bg-amber-50 border-amber-200 text-amber-700 hover:bg-amber-100' : 'bg-white border-gray-200 text-brand-gray hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                        {{ $thread->is_pinned ? 'Unpin' : 'Pin' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('forum.toggleLock', [$course->slug, $thread->slug]) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors {{ $thread->is_locked ? 'bg-red-50 border-red-200 text-red-700 hover:bg-red-100' : 'bg-white border-gray-200 text-brand-gray hover:bg-gray-50' }}">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                        {{ $thread->is_locked ? 'Unlock' : 'Lock' }}
                    </button>
                </form>
            </div>
        @endif

        {{-- Replies --}}
        @if($replies->count())
            <h2 class="text-lg font-bold text-brand-dark mb-4">{{ $replies->count() }} {{ Str::plural('Reply', $replies->count()) }}</h2>

            <div class="space-y-4">
                @foreach($replies as $reply)
                    <div class="bg-white rounded-xl border border-brand-gray-light p-5" id="reply-{{ $reply->id }}">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-blush flex items-center justify-center text-xs font-bold text-brand-brown flex-shrink-0">
                                {{ strtoupper(substr($reply->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-sm text-brand-dark">{{ $reply->user->name ?? 'Unknown' }}</span>
                                    @if($reply->user && $reply->user->hasRole('instructor') && $course->instructor_id === $reply->user->id)
                                        <span class="text-xs font-medium px-1.5 py-0.5 rounded bg-brand-brown/10 text-brand-brown">Instructor</span>
                                    @endif
                                    <span class="text-xs text-brand-gray">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="prose prose-sm max-w-none text-brand-dark">
                                    {!! nl2br(e($reply->body)) !!}
                                </div>

                                <div class="flex items-center gap-3 mt-3">
                                    @if(!$thread->is_locked || $canModerate)
                                        <button onclick="document.getElementById('reply-form-{{ $reply->id }}').classList.toggle('hidden')"
                                                class="text-xs text-brand-gray hover:text-brand-dark transition-colors">Reply</button>
                                    @endif
                                    @if($reply->user_id === auth()->id() || $canModerate)
                                        <form method="POST" action="{{ route('forum.replies.destroy', [$course->slug, $thread->slug, $reply]) }}" class="inline" onsubmit="return confirm('Delete this reply?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors">Delete</button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Inline reply form --}}
                                <div id="reply-form-{{ $reply->id }}" class="hidden mt-3">
                                    <form method="POST" action="{{ route('forum.replies.store', [$course->slug, $thread->slug]) }}">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                        <textarea name="body" rows="3" required
                                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm"
                                                  placeholder="Write a reply..."></textarea>
                                        <div class="flex items-center gap-2 mt-2">
                                            <button type="submit" class="px-4 py-1.5 bg-brand-brown text-white text-xs font-medium rounded-lg hover:bg-brand-brown/90 transition-colors">Post Reply</button>
                                            <button type="button" onclick="this.closest('[id^=reply-form]').classList.add('hidden')" class="text-xs text-brand-gray hover:text-brand-dark">Cancel</button>
                                        </div>
                                    </form>
                                </div>

                                {{-- Nested replies (one level) --}}
                                @if($reply->children->count())
                                    <div class="mt-4 pl-4 border-l-2 border-gray-100 space-y-4">
                                        @foreach($reply->children as $child)
                                            <div class="flex items-start gap-3" id="reply-{{ $child->id }}">
                                                <div class="w-7 h-7 rounded-full bg-brand-blush flex items-center justify-center text-xs font-bold text-brand-brown flex-shrink-0">
                                                    {{ strtoupper(substr($child->user->name ?? 'U', 0, 2)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="font-semibold text-xs text-brand-dark">{{ $child->user->name ?? 'Unknown' }}</span>
                                                        @if($child->user && $child->user->hasRole('instructor') && $course->instructor_id === $child->user->id)
                                                            <span class="text-xs font-medium px-1.5 py-0.5 rounded bg-brand-brown/10 text-brand-brown">Instructor</span>
                                                        @endif
                                                        <span class="text-xs text-brand-gray">{{ $child->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="prose prose-sm max-w-none text-brand-dark text-sm">
                                                        {!! nl2br(e($child->body)) !!}
                                                    </div>
                                                    @if($child->user_id === auth()->id() || $canModerate)
                                                        <form method="POST" action="{{ route('forum.replies.destroy', [$course->slug, $thread->slug, $child]) }}" class="inline mt-2" onsubmit="return confirm('Delete this reply?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Reply Form --}}
        @if(!$thread->is_locked || $canModerate)
            <div class="mt-8 bg-white rounded-xl border border-brand-gray-light p-6">
                <h3 class="text-sm font-semibold text-brand-dark mb-3">Post a Reply</h3>
                <form method="POST" action="{{ route('forum.replies.store', [$course->slug, $thread->slug]) }}">
                    @csrf
                    <textarea name="body" rows="4" required
                              class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm"
                              placeholder="Share your thoughts...">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-3">
                        <button type="submit" class="px-5 py-2.5 bg-brand-brown text-white font-semibold text-sm rounded-lg hover:bg-brand-brown/90 transition-colors">
                            Post Reply
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="mt-8 bg-gray-50 rounded-xl border border-gray-200 p-6 text-center">
                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                <p class="text-sm text-brand-gray">This thread is locked. No new replies can be posted.</p>
            </div>
        @endif
    </div>
</x-public-layout>
