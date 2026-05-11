<x-admin-layout>
    <x-slot name="header">Instructors</x-slot>

    {{-- Action bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <form method="GET" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search instructors..."
                   class="px-4 py-2 rounded-lg border border-gray-200 text-sm w-64 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
            <button type="submit" class="px-4 py-2 bg-brand-dark text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Search</button>
        </form>

        <a href="{{ route('admin.instructors.applications') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 border border-amber-200 text-sm font-medium rounded-lg hover:bg-amber-100 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
            Applications
            @php $pendingCount = \App\Models\InstructorApplication::pending()->count(); @endphp
            @if($pendingCount > 0)
                <span class="bg-amber-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
    </div>

    {{-- Instructors table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">All Instructors</h3>
        </div>

        @if($instructors->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Instructor</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Email</th>
                            <th class="text-center px-6 py-3 font-medium text-brand-gray">Courses</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Total Earnings</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Pending</th>
                            <th class="text-center px-6 py-3 font-medium text-brand-gray">Fee %</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($instructors as $instructor)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-brand-red flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                            {{ strtoupper(substr($instructor->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-brand-dark">{{ $instructor->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-brand-gray">{{ $instructor->email }}</td>
                                <td class="px-6 py-4 text-center text-brand-dark">{{ $instructor->courses_count }}</td>
                                <td class="px-6 py-4 text-right font-medium text-brand-dark">${{ number_format($instructor->total_earnings, 2) }}</td>
                                <td class="px-6 py-4 text-right text-amber-600">${{ number_format($instructor->pending_earnings, 2) }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($instructor->platform_fee_override !== null)
                                        <span class="text-brand-red font-medium">{{ $instructor->platform_fee_override }}%</span>
                                    @else
                                        <span class="text-brand-gray">Global</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end">
                                        <a href="{{ route('admin.instructors.edit', $instructor) }}"
                                           class="p-1.5 text-brand-gray hover:text-brand-dark transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $instructors->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No instructors found.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
