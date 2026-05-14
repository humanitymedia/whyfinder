<x-admin-layout>
    <x-slot name="header">Content</x-slot>

    @if(session('success'))
        <div class="max-w-4xl mb-4 p-4 bg-green-50 border border-green-200 text-green-800 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-4xl mb-4 p-4 bg-red-50 border border-red-200 text-red-800 text-sm rounded-lg">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-4xl space-y-8">

        {{-- Hero Section --}}
        <form method="POST" action="{{ route('admin.content.hero.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Home Hero</h3>
                    <p class="text-sm text-brand-gray mt-1">Top section of the homepage. The background image sits behind a dark gradient overlay.</p>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Hero image --}}
                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-2">Background image</label>
                        @if($hero['image'])
                            <div class="mb-3 relative inline-block">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($hero['image']) }}" alt="Current hero" class="h-32 rounded-lg border border-gray-200 object-cover">
                                <label class="absolute top-2 right-2 flex items-center gap-1 bg-white/90 px-2 py-1 rounded text-xs text-red-600 cursor-pointer hover:bg-white">
                                    <input type="checkbox" name="remove_image" value="1" class="rounded text-red-600">
                                    Remove
                                </label>
                            </div>
                        @endif
                        <input type="file" name="home_hero_image" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-brand-gray file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-cream file:text-brand-dark hover:file:bg-gray-200">
                        <p class="text-xs text-brand-gray mt-1">Recommended: 1920×1080+ JPEG/PNG/WebP, max 5MB. Dark photos work best because of the gradient overlay.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Eyebrow (small red uppercase text)</label>
                        <input type="text" name="eyebrow" value="{{ old('eyebrow', $hero['eyebrow']) }}"
                               maxlength="120"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Headline <span class="text-red-500">*</span></label>
                        <input type="text" name="headline" value="{{ old('headline', $hero['headline']) }}"
                               maxlength="255" required
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">
                        <p class="text-xs text-brand-gray mt-1">Use <code class="bg-gray-100 px-1 rounded">&lt;br&gt;</code> for a line break.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Subheadline</label>
                        <textarea name="subhead" rows="3" maxlength="1000"
                                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">{{ old('subhead', $hero['subhead']) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Primary CTA label</label>
                            <input type="text" name="cta_primary" value="{{ old('cta_primary', $hero['cta_primary']) }}"
                                   maxlength="60"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">
                            <p class="text-xs text-brand-gray mt-1">Links to /register.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-brand-gray mb-1">Secondary CTA label</label>
                            <input type="text" name="cta_secondary" value="{{ old('cta_secondary', $hero['cta_secondary']) }}"
                                   maxlength="60"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">
                            <p class="text-xs text-brand-gray mt-1">Links to /courses.</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Save Hero
                    </button>
                </div>
            </div>
        </form>

        {{-- How It Works --}}
        <form method="POST" action="{{ route('admin.content.how-it-works.update') }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">How It Works</h3>
                    <p class="text-sm text-brand-gray mt-1">Three-step section on the homepage. Icons are fixed (search → book → bolt).</p>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Section intro</label>
                        <textarea name="intro" rows="2" maxlength="500"
                                  class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">{{ old('intro', $howItWorks['intro']) }}</textarea>
                    </div>

                    @foreach([1, 2, 3] as $i)
                        <div class="grid grid-cols-3 gap-4 pt-4 {{ $i > 1 ? 'border-t border-gray-100' : '' }}">
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Step {{ $i }} title <span class="text-red-500">*</span></label>
                                <input type="text" name="step{{ $i }}_title" value="{{ old('step'.$i.'_title', $howItWorks['step'.$i.'_title']) }}"
                                       maxlength="120" required
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-brand-gray mb-1">Step {{ $i }} body <span class="text-red-500">*</span></label>
                                <textarea name="step{{ $i }}_body" rows="2" maxlength="500" required
                                          class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">{{ old('step'.$i.'_body', $howItWorks['step'.$i.'_body']) }}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Save How It Works
                    </button>
                </div>
            </div>
        </form>

        {{-- Branding --}}
        <form method="POST" action="{{ route('admin.content.branding.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Branding</h3>
                    <p class="text-sm text-brand-gray mt-1">Site logo and footer tagline.</p>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-2">Site logo</label>
                        @if($branding['site_logo'])
                            <div class="mb-3 relative inline-block bg-brand-brown p-3 rounded-lg">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($branding['site_logo']) }}" alt="Current logo" class="h-10">
                                <label class="absolute top-1 right-1 flex items-center gap-1 bg-white/90 px-2 py-0.5 rounded text-xs text-red-600 cursor-pointer hover:bg-white">
                                    <input type="checkbox" name="remove_logo" value="1" class="rounded text-red-600">
                                    Remove
                                </label>
                            </div>
                        @endif
                        <input type="file" name="site_logo" accept="image/jpeg,image/png,image/webp,image/svg+xml"
                               class="block w-full text-sm text-brand-gray file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-brand-cream file:text-brand-dark hover:file:bg-gray-200">
                        <p class="text-xs text-brand-gray mt-1">SVG preferred. Max 2MB. Leave blank to keep the default WhyFinder mark.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-brand-gray mb-1">Footer tagline</label>
                        <input type="text" name="footer_tagline" value="{{ old('footer_tagline', $branding['footer_tagline']) }}"
                               maxlength="255"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red">
                        <p class="text-xs text-brand-gray mt-1">Shown italic at the bottom-right of the footer.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Save Branding
                    </button>
                </div>
            </div>
        </form>

    </div>
</x-admin-layout>
