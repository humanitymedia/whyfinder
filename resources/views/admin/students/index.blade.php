<x-admin-layout>
    <x-slot name="header">Students</x-slot>

    {{-- Search --}}
    <div class="mb-6">
        <form method="GET" class="flex items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..."
                   class="px-4 py-2 rounded-lg border border-gray-200 text-sm w-64 focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
            <button type="submit" class="px-4 py-2 bg-brand-dark text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">Search</button>
        </form>
    </div>

    {{-- Students table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">All Students</h3>
        </div>

        @if($students->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Student</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Email</th>
                            <th class="text-center px-6 py-3 font-medium text-brand-gray">Courses</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Total Spent</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Joined</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $student)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold text-blue-700 flex-shrink-0">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-brand-dark">{{ $student->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-brand-gray">{{ $student->email }}</td>
                                <td class="px-6 py-4 text-center text-brand-dark">{{ $student->enrollments_count }}</td>
                                <td class="px-6 py-4 text-right font-medium text-brand-dark">${{ number_format($student->total_spent, 2) }}</td>
                                <td class="px-6 py-4 text-brand-gray">{{ $student->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end">
                                        <a href="{{ route('admin.students.show', $student) }}"
                                           class="p-1.5 text-brand-gray hover:text-brand-dark transition-colors" title="View Details">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $students->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No students found.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
