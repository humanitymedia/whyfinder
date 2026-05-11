<x-admin-layout>
    <x-slot name="header">Instructor Applications</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.instructors.index') }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">&larr; Back to Instructors</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">All Applications</h3>
        </div>

        @if($applications->count())
            <div class="divide-y divide-gray-100">
                @foreach($applications as $application)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h4 class="font-semibold text-brand-dark">{{ $application->first_name }} {{ $application->last_name }}</h4>
                                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $application->status_badge_color }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-brand-gray mb-1">{{ $application->email }}</p>
                                <p class="text-sm text-brand-dark"><span class="text-brand-gray">Expertise:</span> {{ $application->area_of_expertise }}</p>
                                <p class="text-sm text-brand-gray mt-2">{{ Str::limit($application->bio, 200) }}</p>
                                @if($application->website_url)
                                    <p class="text-sm mt-1"><a href="{{ $application->website_url }}" target="_blank" class="text-brand-red hover:underline">{{ $application->website_url }}</a></p>
                                @endif
                                <p class="text-xs text-brand-gray mt-2">Applied {{ $application->created_at->diffForHumans() }}
                                    @if($application->user)
                                        &middot; Registered user: {{ $application->user->name }}
                                    @endif
                                </p>
                                @if($application->reviewed_at)
                                    <p class="text-xs text-brand-gray mt-1">
                                        Reviewed {{ $application->reviewed_at->diffForHumans() }}
                                        @if($application->reviewer)
                                            by {{ $application->reviewer->name }}
                                        @endif
                                    </p>
                                @endif
                            </div>

                            @if($application->status === 'pending')
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <form method="POST" action="{{ route('admin.instructors.approve', $application) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.instructors.reject', $application) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-4">
                {{ $applications->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No instructor applications found.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
