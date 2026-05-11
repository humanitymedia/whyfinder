@props(['class' => ''])

<a href="/" class="flex items-center gap-2 {{ $class }}">
    <img src="{{ asset('images/whyfinder-logo-white.svg') }}" alt="WhyFinder" class="h-8 w-8">
    <span class="text-xl font-bold tracking-tight">Why<span class="font-normal">Finder</span></span>
</a>
