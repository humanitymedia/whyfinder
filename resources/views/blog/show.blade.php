<x-public-layout>
    <x-slot name="title">{{ $post->meta_title ?? $post->title }}</x-slot>

    <article class="max-w-3xl mx-auto px-4 py-12">
        {{-- Back link --}}
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1 text-sm text-brand-gray hover:text-brand-red transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            Back to blog
        </a>

        {{-- Category badge --}}
        @if($post->category)
            <span class="inline-block text-xs font-medium text-brand-red bg-red-50 px-3 py-1 rounded-full">{{ $post->category->name }}</span>
        @endif

        {{-- Title --}}
        <h1 class="text-3xl md:text-4xl font-serif font-bold text-brand-dark mt-4 mb-4 leading-tight">{{ $post->title }}</h1>

        {{-- Meta --}}
        <div class="flex items-center gap-4 text-sm text-brand-gray mb-10">
            <span class="font-medium text-brand-dark">{{ $post->author->name ?? 'WhyFinder' }}</span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                {{ $post->published_at->format('F j, Y') }}
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ $post->read_time_minutes ?? 5 }} min read
            </span>
        </div>

        {{-- Featured image --}}
        @if($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-xl mb-10">
        @endif

        {{-- Article body --}}
        @if($post->excerpt)
            <p class="text-lg text-brand-gray leading-relaxed mb-6">{{ $post->excerpt }}</p>
        @endif

        <div class="prose prose-lg max-w-none prose-headings:font-serif prose-headings:text-brand-dark prose-p:text-brand-dark prose-a:text-brand-red hover:prose-a:text-red-700">
            {!! Str::markdown($post->body ?? '') !!}
        </div>

        {{-- Tags --}}
        @if($post->tags->count())
            <div class="flex flex-wrap gap-2 mt-10 pt-6 border-t border-gray-200">
                @foreach($post->tags as $tag)
                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-brand-gray">{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif

        {{-- CTA Card --}}
        <div class="mt-12 p-8 bg-brand-cream border border-brand-gray-light rounded-xl text-center">
            <h2 class="font-serif text-xl font-bold text-brand-dark mb-2">Ready to find your why?</h2>
            <p class="text-brand-gray mb-4">Start with our free foundational course.</p>
            <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 bg-brand-red text-white px-6 py-3 rounded-lg font-medium hover:bg-red-700 transition-colors">
                Start Free Course
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </a>
        </div>
    </article>
</x-public-layout>
