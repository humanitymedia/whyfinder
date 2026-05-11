<x-admin-layout>
    <x-slot name="header">Student: {{ $user->name }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.students.index') }}" class="text-sm text-brand-gray hover:text-brand-dark transition-colors">&larr; Back to Students</a>
    </div>

    {{-- Student info --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-lg font-bold text-blue-700">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-brand-dark">{{ $user->name }}</h2>
                <p class="text-sm text-brand-gray">{{ $user->email }} &middot; Joined {{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <div x-data="{ tab: 'courses' }" class="space-y-6">
        {{-- Tab navigation --}}
        <div class="flex gap-1 bg-white rounded-lg border border-gray-200 p-1 w-fit">
            <button @click="tab = 'courses'"
                    :class="tab === 'courses' ? 'bg-brand-dark text-white' : 'text-brand-gray hover:text-brand-dark'"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors">
                Courses ({{ $coursesWithProgress->count() }})
            </button>
            <button @click="tab = 'payments'"
                    :class="tab === 'payments' ? 'bg-brand-dark text-white' : 'text-brand-gray hover:text-brand-dark'"
                    class="px-4 py-2 text-sm font-medium rounded-md transition-colors">
                Payments ({{ $payments->count() }})
            </button>
        </div>

        {{-- Enrolled Courses + Progress --}}
        <div x-show="tab === 'courses'" x-cloak>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Enrolled Courses</h3>
                </div>

                @if($coursesWithProgress->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Enrolled</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Progress</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($coursesWithProgress as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-brand-dark">{{ $item->course->title }}</td>
                                        <td class="px-6 py-4 text-brand-gray">{{ $item->enrolled_at?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-32 h-2 bg-gray-100 rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full {{ $item->progress >= 100 ? 'bg-green-500' : 'bg-brand-red' }}"
                                                         style="width: {{ $item->progress }}%"></div>
                                                </div>
                                                <span class="text-xs text-brand-gray whitespace-nowrap">{{ $item->completed_lessons }}/{{ $item->total_lessons }} ({{ $item->progress }}%)</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($item->completed_at)
                                                <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">Completed</span>
                                            @elseif($item->progress > 0)
                                                <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">In Progress</span>
                                            @else
                                                <span class="inline-flex text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">Not Started</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center text-brand-gray">
                        <p>This student hasn't enrolled in any courses yet.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment History --}}
        <div x-show="tab === 'payments'" x-cloak>
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Payment History</h3>
                </div>

                @if($payments->count())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Date</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                                    <th class="text-right px-6 py-3 font-medium text-brand-gray">Amount</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-brand-gray">{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-brand-dark">{{ $payment->course->title ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-brand-dark">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $payment->status_badge_color }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center text-brand-gray">
                        <p>No payment history for this student.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
