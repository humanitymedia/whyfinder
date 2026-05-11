<x-public-layout>
    <x-slot name="title">My Certificates — WhyFinder</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">My Certificates</h1>
            <p class="text-gray-300">Download your course completion certificates.</p>
        </div>
    </section>

    <section class="py-10 bg-brand-cream">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($certificates->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($certificates as $certificate)
                        <div class="bg-white rounded-xl border border-brand-gray-light p-6 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-brand-dark text-sm truncate">{{ $certificate->course->title }}</h3>
                                    <p class="text-xs text-brand-gray">Issued {{ $certificate->issued_at->format('M j, Y') }}</p>
                                </div>
                            </div>

                            <p class="text-xs text-brand-gray mb-4">Certificate #{{ $certificate->certificate_number }}</p>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('certificates.download', $certificate) }}"
                                   class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-brand-red text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download PDF
                                </a>
                                <a href="{{ route('certificates.verify', $certificate->certificate_number) }}"
                                   class="px-3 py-2 text-xs text-brand-gray hover:text-brand-dark border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                   title="Verify">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <svg class="w-16 h-16 text-brand-gray/30 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h3 class="text-xl font-bold text-brand-dark mb-2">No certificates yet</h3>
                    <p class="text-brand-gray mb-6">Complete a course to earn your first certificate.</p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
                        Browse Courses
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-public-layout>
