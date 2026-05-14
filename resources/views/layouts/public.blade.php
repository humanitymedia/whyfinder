<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'WhyFinder — Discover Your Why. Build Your Life.' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-brand-cream text-brand-dark">
        {{-- Navigation --}}
        <nav x-data="{ mobileOpen: false }" class="bg-white border-t-4 border-brand-brown sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- Logo --}}
                    <div class="flex-shrink-0">
                        <x-whyfinder-logo />
                    </div>

                    {{-- Center nav links (desktop) --}}
                    <div class="hidden md:flex items-center gap-8">
                        <a href="{{ route('courses.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('courses.*') ? 'text-brand-red' : 'text-brand-dark hover:text-brand-red' }} transition-colors">
                            Courses
                        </a>
                        <a href="{{ route('blog.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('blog.*') ? 'text-brand-red' : 'text-brand-dark hover:text-brand-red' }} transition-colors">
                            Blog
                        </a>
                        <a href="{{ route('teach') }}"
                           class="text-sm font-medium {{ request()->routeIs('teach') ? 'text-brand-red' : 'text-brand-dark hover:text-brand-red' }} transition-colors">
                            Teach
                        </a>
                    </div>

                    {{-- Right side (desktop) --}}
                    <div class="hidden md:flex items-center gap-4">
                        @auth
                            <a href="{{ route('student.dashboard') }}" class="text-sm font-medium text-brand-dark hover:text-brand-red transition-colors">
                                My Learning
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-brand-gray hover:text-brand-red transition-colors">
                                    Log Out
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-brand-dark hover:text-brand-red transition-colors">
                                Log In
                            </a>
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-5 py-2 bg-brand-red text-white text-sm font-semibold rounded-full hover:bg-red-700 transition-colors">
                                Get Started Free
                            </a>
                        @endauth
                    </div>

                    {{-- Mobile hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-md text-brand-gray hover:text-brand-dark focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileOpen" x-cloak x-transition class="md:hidden border-t border-brand-gray-light">
                <div class="px-4 py-3 space-y-2">
                    <a href="{{ route('courses.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('courses.*') ? 'text-brand-red bg-red-50' : 'text-brand-dark hover:bg-gray-50' }}">Courses</a>
                    <a href="{{ route('blog.index') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('blog.*') ? 'text-brand-red bg-red-50' : 'text-brand-dark hover:bg-gray-50' }}">Blog</a>
                    <a href="{{ route('teach') }}" class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('teach') ? 'text-brand-red bg-red-50' : 'text-brand-dark hover:bg-gray-50' }}">Teach</a>
                    <div class="border-t border-brand-gray-light pt-2 mt-2">
                        @auth
                            <a href="{{ route('student.dashboard') }}" class="block px-3 py-2 text-sm font-medium text-brand-dark hover:bg-gray-50 rounded-md">My Learning</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-sm font-medium text-brand-gray hover:bg-gray-50 rounded-md">Log Out</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-brand-dark hover:bg-gray-50 rounded-md">Log In</a>
                            <a href="{{ route('register') }}" class="block mx-3 mt-2 px-5 py-2 bg-brand-red text-white text-sm font-semibold rounded-full text-center hover:bg-red-700 transition-colors">Get Started Free</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Page content --}}
        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-brand-brown text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    {{-- Logo & tagline --}}
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="32" height="32" rx="6" fill="#DC2626"/>
                                <path d="M10 8L16 18L22 8" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16 18V25" stroke="white" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                            <span class="text-lg font-bold">Why<span class="font-normal">Finder</span></span>
                        </div>
                        <p class="text-sm text-gray-300 leading-relaxed">
                            Helping people discover their purpose and build a life around it.
                        </p>
                    </div>

                    {{-- Learn --}}
                    <div>
                        <h4 class="font-semibold text-sm uppercase tracking-wider mb-4">Learn</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('courses.index') }}" class="text-sm text-gray-300 hover:text-white transition-colors">All Courses</a></li>
                            <li><a href="{{ route('courses.index') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Find Your Why (Free)</a></li>
                            <li><a href="{{ route('blog.index') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Blog</a></li>
                        </ul>
                    </div>

                    {{-- Teach --}}
                    <div>
                        <h4 class="font-semibold text-sm uppercase tracking-wider mb-4">Teach</h4>
                        <ul class="space-y-2">
                            <li><a href="{{ route('teach') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Become an Instructor</a></li>
                            @auth
                                @if(auth()->user()->hasRole('instructor') || auth()->user()->hasRole('admin'))
                                    <li><a href="#" class="text-sm text-gray-300 hover:text-white transition-colors">Instructor Dashboard</a></li>
                                @endif
                            @endauth
                        </ul>
                    </div>

                    {{-- Account --}}
                    <div>
                        <h4 class="font-semibold text-sm uppercase tracking-wider mb-4">Account</h4>
                        <ul class="space-y-2">
                            @auth
                                <li><a href="{{ route('dashboard') }}" class="text-sm text-gray-300 hover:text-white transition-colors">My Dashboard</a></li>
                                <li><a href="{{ route('profile.edit') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Profile</a></li>
                            @else
                                <li><a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Log In</a></li>
                                <li><a href="{{ route('register') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Sign Up</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Copyright bar --}}
            <div class="border-t border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p class="text-xs text-gray-400">&copy; {{ date('Y') }} WhyFinder. All rights reserved.</p>
                    <p class="text-xs text-gray-400 italic">{{ \App\Models\Setting::get('footer_tagline', 'Live with purpose. Build with intention.') }}</p>
                </div>
            </div>
        </footer>
    </body>
</html>
