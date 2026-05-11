<x-admin-layout>
    <x-slot name="title">Reviews</x-slot>
    <x-slot name="header">Review Management</x-slot>

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Total Reviews</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ number_format($totalReviews) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Pending Reviews</p>
            <p class="text-2xl font-bold {{ $pendingReviews > 0 ? 'text-amber-600' : 'text-brand-dark' }} mt-1">{{ number_format($pendingReviews) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-brand-gray">Average Rating</p>
            <p class="text-2xl font-bold text-brand-dark mt-1">{{ $averageRating ? number_format($averageRating, 1) : '—' }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or course..."
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
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div>
                <select name="rating" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-brown focus:ring-brand-brown text-sm">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} {{ Str::plural('star', $i) }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-brand-brown text-white text-sm font-medium rounded-lg hover:bg-brand-brown/90 transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'course', 'status', 'rating']))
                <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 text-sm text-brand-gray hover:text-brand-dark transition-colors text-center">Clear</a>
            @endif
        </form>
    </div>

    {{-- Reviews Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-dark">{{ Str::limit($review->course->title ?? 'Deleted', 30) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-gray">{{ $review->user->name ?? 'Deleted' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-amber-500' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-gray">{{ Str::limit($review->comment, 40) ?: '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($review->is_approved)
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-green-100 text-green-700">Approved</span>
                                @else
                                    <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-brand-gray">{{ $review->created_at->format('M j, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <form method="POST" action="{{ route('admin.reviews.toggleApproval', $review) }}">
                                        @csrf
                                        <button type="submit" title="{{ $review->is_approved ? 'Unapprove' : 'Approve' }}"
                                                class="p-1.5 rounded-lg transition-colors {{ $review->is_approved ? 'text-green-600 hover:bg-green-50' : 'text-amber-600 hover:bg-amber-50' }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
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
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-brand-gray">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</x-admin-layout>
