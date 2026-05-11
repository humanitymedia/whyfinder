<x-public-layout>
    <x-slot name="title">Verify Certificate — WhyFinder</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Certificate Verification</h1>
            <p class="text-gray-300">Verify the authenticity of a WhyFinder certificate.</p>
        </div>
    </section>

    <section class="py-12 bg-brand-cream">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('certificates.verify', '') }}" class="mb-8"
                  onsubmit="if(this.number.value) { this.action = '{{ url('/verify') }}/' + this.number.value; } return !!this.number.value;">
                <label class="block text-sm font-medium text-brand-dark mb-2">Certificate Number</label>
                <div class="flex gap-2">
                    <input type="text" name="number" value="{{ $certificateNumber ?? '' }}" placeholder="e.g. WF-AB1CD2EF"
                           class="flex-1 px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors">
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Verify
                    </button>
                </div>
            </form>

            {{-- Result --}}
            @if(isset($certificate) && $certificate)
                <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-green-800">Certificate Verified</h3>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-green-700">Student</span>
                            <span class="font-medium text-green-900">{{ $certificate->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-green-700">Course</span>
                            <span class="font-medium text-green-900">{{ $certificate->course->title }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-green-700">Issued</span>
                            <span class="font-medium text-green-900">{{ $certificate->issued_at->format('F j, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-green-700">Certificate #</span>
                            <span class="font-medium text-green-900">{{ $certificate->certificate_number }}</span>
                        </div>
                    </div>
                </div>
            @elseif(isset($certificateNumber) && $certificateNumber)
                <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-red-800">Certificate Not Found</h3>
                            <p class="text-sm text-red-600 mt-1">No certificate was found with the number "{{ $certificateNumber }}". Please check the number and try again.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
