<x-admin-layout>
    <x-slot name="header">Payment Reports</x-slot>

    {{-- Stats cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['total_revenue'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Total Revenue</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['total_platform_fees'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">Platform Fees Earned</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-brand-dark">${{ number_format($stats['this_month_revenue'], 2) }}</p>
            <p class="text-sm text-brand-gray mt-1">This Month</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <p class="text-lg font-bold text-brand-dark">{{ $stats['total_transactions'] }}</p>
            <p class="text-sm text-brand-gray">Total Transactions</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <p class="text-lg font-bold text-brand-dark">${{ number_format($stats['pending_payouts'], 2) }}</p>
            <p class="text-sm text-brand-gray">Pending Instructor Payouts</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
            <p class="text-lg font-bold text-brand-dark">${{ number_format($stats['total_instructor_payouts'], 2) }}</p>
            <p class="text-sm text-brand-gray">Total Paid to Instructors</p>
        </div>
    </div>

    {{-- Transactions table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold text-brand-dark">All Transactions</h3>
        </div>

        @if($payments->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Date</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Student</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Amount</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Platform Fee</th>
                            <th class="text-right px-6 py-3 font-medium text-brand-gray">Instructor Net</th>
                            <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-brand-gray">{{ $payment->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-brand-dark">{{ $payment->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 font-medium text-brand-dark">{{ $payment->course->title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-right font-medium text-brand-dark">${{ number_format($payment->amount, 2) }}</td>
                                <td class="px-6 py-4 text-right text-brand-gray">
                                    @if($payment->instructorEarning)
                                        ${{ number_format($payment->instructorEarning->platform_fee, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-brand-gray">
                                    @if($payment->instructorEarning)
                                        ${{ number_format($payment->instructorEarning->net_amount, 2) }}
                                    @else
                                        —
                                    @endif
                                </td>
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

            <div class="px-6 py-4">
                {{ $payments->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center text-brand-gray">
                <p>No payments recorded yet.</p>
            </div>
        @endif
    </div>
</x-admin-layout>
