<x-instructor-layout>
    <x-slot name="header">Earnings</x-slot>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['total_earnings'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Earnings</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['pending_earnings'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Pending Payout</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['paid_earnings'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Paid Out</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['this_month'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">This Month</p>
        </div>
    </div>

    {{-- Earnings table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">Earnings History</h3>
        </div>

        @if($earnings->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Date</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Student</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Gross</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Platform Fee</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Net Earnings</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($earnings as $earning)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-brand-gray">{{ $earning->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 font-medium text-brand-dark">{{ $earning->course->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-brand-gray">{{ $earning->payment->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-right text-brand-gray">${{ number_format($earning->gross_amount, 2) }}</td>
                                <td class="px-6 py-4 text-right text-brand-gray">-${{ number_format($earning->platform_fee, 2) }}</td>
                                <td class="px-6 py-4 text-right font-medium text-brand-dark">${{ number_format($earning->net_amount, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $earning->status_badge_color }}">
                                        {{ ucfirst($earning->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $earnings->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No earnings yet. Earnings will appear here when students purchase your courses.</p>
            </div>
        @endif
    </div>
</x-instructor-layout>
