<x-public-layout>
    <x-slot name="title">Payment History — WhyFinder</x-slot>

    <section class="bg-brand-brown py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Payment History</h1>
            <p class="text-gray-300">View all your course purchases and transactions.</p>
        </div>
    </section>

    <section class="py-10 bg-brand-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($payments->count())
                <div class="bg-white rounded-xl border border-brand-gray-light overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Date</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Course</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Amount</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Status</th>
                                    <th class="text-left px-6 py-3 font-medium text-brand-gray">Type</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-brand-gray">{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td class="px-6 py-4">
                                            @if($payment->course)
                                                <a href="{{ route('courses.show', $payment->course->slug) }}" class="font-medium text-brand-dark hover:text-brand-red transition-colors">
                                                    {{ $payment->course->title }}
                                                </a>
                                            @else
                                                <span class="text-brand-gray">Deleted course</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 font-medium text-brand-dark">${{ number_format($payment->amount, 2) }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex text-xs px-2 py-0.5 rounded-full {{ $payment->status_badge_color }}">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-brand-gray">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-brand-gray/30 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                    <h3 class="text-xl font-bold text-brand-dark mb-2">No payments yet</h3>
                    <p class="text-brand-gray mb-6">Your purchase history will appear here after you buy a course.</p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
                        Browse Courses
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
