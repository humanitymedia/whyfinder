@props(['class' => ''])

@php
    $customLogo = \App\Models\Setting::get('site_logo');
@endphp

<a href="/" class="flex items-center gap-2 {{ $class }}">
    @if($customLogo)
        <img src="{{ \Illuminate\Support\Facades\Storage::url($customLogo) }}" alt="WhyFinder" class="h-8 w-auto max-w-[160px]">
    @else
        <img src="{{ asset('images/whyfinder-logo-white.svg') }}" alt="WhyFinder" class="h-8 w-8">
        <span class="text-xl font-bold tracking-tight">Why<span class="font-normal">Finder</span></span>
    @endif
</a>
