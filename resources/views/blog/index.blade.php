<x-public-layout>
    <x-slot name="title">Blog</x-slot>

    {{-- Header --}}
    <section class="bg-brand-brown text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold">Blog</h1>
            <p class="mt-3 text-gray-300 max-w-2xl">Stories, insights, and practical wisdom for living and building with purpose.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Featured Post --}}
        @if($featuredPost)
            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="block mb-12 group">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden md:flex">
                    {{-- Image --}}
                    <div class="md:w-1/2 aspect-video md:aspect-auto bg-gradient-to-br from-brand-red/10 to-brand-blush flex items-center justify-center p-8">
                        @if($featuredPost->featured_image)
                            <img src="{{ Storage::url($featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover">
                        @else
                            <span class="font-serif text-2xl text-brand-gray/40 text-center">{{ $featuredPost->title }}</span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-8 md:w-1/2 flex flex-col justify-center space-y-4">
                        <span class="text-xs font-medium text-brand-red bg-red-50 px-3 py-1 rounded-full w-fit">Featured</span>
                        <h2 class="text-2xl font-serif font-bold text-brand-dark group-hover:text-brand-red transition-colors">{{ $featuredPost->title }}</h2>
                        @if($featuredPost->excerpt)
                            <p class="text-brand-gray leading-relaxed">{{ $featuredPost->excerpt }}</p>
                        @endif
                        <div class="flex items-center gap-4 text-sm text-brand-gray">
                            <span>{{ $featuredPost->author->name ?? 'WhyFinder' }}</span>
                            <span>&middot;</span>
                            <span>{{ $featuredPost->read_time_minutes ?? 5 }} min read</span>
                        </div>
                    </div>
                </div>
            </a>
        @endif

        {{-- Post Grid --}}
        @if($posts->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all group">
                        {{-- Image --}}
                        <div class="aspect-video bg-gradient-to-br from-brand-cream to-brand-gray-light flex items-center justify-center p-4">
                            @if($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-serif text-sm text-brand-gray/40 text-center">{{ $post->title }}</span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-5 space-y-3">
                            @if($post->category)
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-red-50 text-brand-red border border-red-100">{{ $post->category->name }}</span>
                            @endif
                            <h3 class="font-serif text-lg font-bold text-brand-dark group-hover:text-brand-red transition-colors leading-snug">{{ $post->title }}</h3>
                            @if($post->excerpt)
                                <p class="text-sm text-brand-gray line-clamp-2">{{ $post->excerpt }}</p>
                            @endif
                            <div class="flex items-center gap-3 text-xs text-brand-gray pt-1">
                                <span>{{ $post->author->name ?? 'WhyFinder' }}</span>
                                <span>&middot;</span>
                                <span>{{ $post->read_time_minutes ?? 5 }} min read</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @elseif(!$featuredPost)
            <div class="text-center py-20">
                <svg class="w-16 h-16 text-brand-gray-light mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" /></svg>
                <h3 class="text-lg font-semibold text-brand-dark mb-2">No blog posts yet</h3>
                <p class="text-brand-gray">Check back soon for new articles and insights.</p>
            </div>
        @endif
    </div>
</x-public-layout>
