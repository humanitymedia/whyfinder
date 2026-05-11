<x-admin-layout>
    <x-slot name="title">Forums</x-slot>
    <x-slot name="header">Forum Moderation</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Total Threads</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ number_format($totalThreads) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Total Replies</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ number_format($totalReplies) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Unapproved Replies</p>
            <p class="text-2xl font-bold {{ $unapprovedReplies > 0 ? 'text-amber-600' : 'text-brand-dark' }} mt-1">{{ number_format($unapprovedReplies) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.forums.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search threads..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
            </div>
            <div>
                <select name="course" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                    <option value="">All Statuses</option>
                    <option value="pinned" {{ request('status') === 'pinned' ? 'selected' : '' }}>Pinned</option>
                    <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Locked</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-brown text-white text-sm font-medium rounded-lg hover:bg-brand-brown/90 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'course', 'status']))
                <a href="{{ route('admin.forums.index') }}" class="px-4 py-2 text-sm text-brand-gray hover:text-brand-dark transition-colors text-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- Threads Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thread</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Replies</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($threads as $thread)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('forum.show', [$thread->course->slug, $thread->slug]) }}" class="text-sm font-medium text-brand-dark hover:text-brand-red transition-colors" target="_blank">
                                    {{ Str::limit($thread->title, 50) }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-gray">{{ Str::limit($thread->course->title, 30) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-gray">{{ $thread->user->name ?? 'Deleted' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-brand-gray">{{ $thread->approved_replies_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    @if($thread->is_pinned)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Pinned</span>
                                    @endif
                                    @if($thread->is_locked)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-red-100 text-red-700">Locked</span>
                                    @endif
                                    @if(!$thread->is_pinned && !$thread->is_locked)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">Active</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-gray">{{ $thread->created_at->format('M j, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <form method="POST" action="{{ route('admin.forums.togglePin', $thread) }}">
                                        @csrf
                                        <button type="submit" title="{{ $thread->is_pinned ? 'Unpin' : 'Pin' }}"
                                                class="p-1.5 rounded-lg transition-colors {{ $thread->is_pinned ? 'text-amber-600 hover:bg-amber-50' : 'text-gray-400 hover:bg-gray-100 hover:text-gray-600' }}">
                                            <svg class="w-4 h-4" fill="{{ $thread->is_pinned ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.forums.toggleLock', $thread) }}">
                                        @csrf
                                        <button type="submit" title="{{ $thread->is_locked ? 'Unlock' : 'Lock' }}"
                                                class="p-1.5 rounded-lg transition-colors {{ $thread->is_locked ? 'text-red-600 hover:bg-red-50' : 'text-gray-400 hover:bg-gray-100 hover:text-gray-600' }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.forums.destroy', $thread) }}" onsubmit="return confirm('Delete this thread and all its replies?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Delete" class="p-1.5 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-brand-gray">No threads found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($threads->hasPages())
        <div class="mt-6">
            {{ $threads->links() }}
        </div>
    @endif
</x-admin-layout>
