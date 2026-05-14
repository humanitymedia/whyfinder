<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(): View
    {
        $branding = [
            'site_logo' => Setting::get('site_logo'),
            'footer_tagline' => Setting::get('footer_tagline', 'Live with purpose. Build with intention.'),
        ];

        $hero = [
            'image' => Setting::get('home_hero_image'),
            'eyebrow' => Setting::get('home_hero_eyebrow', 'Your Purpose Is Waiting'),
            'headline' => Setting::get('home_hero_headline', 'Discover Your Why. Build Your Life.'),
            'subhead' => Setting::get('home_hero_subhead', 'Free courses to help you find your purpose. Paid courses to help you build a business and life around it. Start your journey today.'),
            'cta_primary' => Setting::get('home_hero_cta_primary', 'Start Free Course'),
            'cta_secondary' => Setting::get('home_hero_cta_secondary', 'Browse Courses'),
        ];

        $howItWorks = [
            'intro' => Setting::get('how_it_works_intro', 'Three simple steps to discovering your purpose and building a life around it.'),
            'step1_title' => Setting::get('how_it_works_step1_title', 'Find Your Why'),
            'step1_body' => Setting::get('how_it_works_step1_body', 'Take our free course to uncover your passions, strengths, and the purpose that drives you.'),
            'step2_title' => Setting::get('how_it_works_step2_title', 'Build Your Skills'),
            'step2_body' => Setting::get('how_it_works_step2_body', 'Learn practical skills like website building, branding, marketing, and entrepreneurship from real instructors.'),
            'step3_title' => Setting::get('how_it_works_step3_title', 'Live Your Purpose'),
            'step3_body' => Setting::get('how_it_works_step3_body', 'Apply what you learn to build a business and life that aligns with who you truly are.'),
        ];

        return view('admin.content.index', compact('branding', 'hero', 'howItWorks'));
    }

    public function updateBranding(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_logo' => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:2048'],
            'remove_logo' => ['nullable', 'boolean'],
            'footer_tagline' => ['nullable', 'string', 'max:255'],
        ]);

        if ($request->boolean('remove_logo')) {
            $this->deleteStoredFile(Setting::get('site_logo'));
            Setting::set('site_logo', null, 'string', 'branding');
        } elseif ($request->hasFile('site_logo')) {
            $this->deleteStoredFile(Setting::get('site_logo'));
            $path = $request->file('site_logo')->store('content/branding', 'public');
            Setting::set('site_logo', $path, 'string', 'branding');
        }

        Setting::set('footer_tagline', $validated['footer_tagline'] ?? '', 'string', 'branding');

        return back()->with('success', 'Branding updated.');
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'home_hero_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'headline' => ['required', 'string', 'max:255'],
            'subhead' => ['nullable', 'string', 'max:1000'],
            'cta_primary' => ['nullable', 'string', 'max:60'],
            'cta_secondary' => ['nullable', 'string', 'max:60'],
        ]);

        if ($request->boolean('remove_image')) {
            $this->deleteStoredFile(Setting::get('home_hero_image'));
            Setting::set('home_hero_image', null, 'string', 'home');
        } elseif ($request->hasFile('home_hero_image')) {
            $this->deleteStoredFile(Setting::get('home_hero_image'));
            $path = $request->file('home_hero_image')->store('content/hero', 'public');
            Setting::set('home_hero_image', $path, 'string', 'home');
        }

        Setting::set('home_hero_eyebrow', $validated['eyebrow'] ?? '', 'string', 'home');
        Setting::set('home_hero_headline', $validated['headline'], 'string', 'home');
        Setting::set('home_hero_subhead', $validated['subhead'] ?? '', 'string', 'home');
        Setting::set('home_hero_cta_primary', $validated['cta_primary'] ?? '', 'string', 'home');
        Setting::set('home_hero_cta_secondary', $validated['cta_secondary'] ?? '', 'string', 'home');

        return back()->with('success', 'Hero section updated.');
    }

    public function updateHowItWorks(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'intro' => ['nullable', 'string', 'max:500'],
            'step1_title' => ['required', 'string', 'max:120'],
            'step1_body' => ['required', 'string', 'max:500'],
            'step2_title' => ['required', 'string', 'max:120'],
            'step2_body' => ['required', 'string', 'max:500'],
            'step3_title' => ['required', 'string', 'max:120'],
            'step3_body' => ['required', 'string', 'max:500'],
        ]);

        Setting::set('how_it_works_intro', $validated['intro'] ?? '', 'string', 'home');
        Setting::set('how_it_works_step1_title', $validated['step1_title'], 'string', 'home');
        Setting::set('how_it_works_step1_body', $validated['step1_body'], 'string', 'home');
        Setting::set('how_it_works_step2_title', $validated['step2_title'], 'string', 'home');
        Setting::set('how_it_works_step2_body', $validated['step2_body'], 'string', 'home');
        Setting::set('how_it_works_step3_title', $validated['step3_title'], 'string', 'home');
        Setting::set('how_it_works_step3_body', $validated['step3_body'], 'string', 'home');

        return back()->with('success', 'How It Works updated.');
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
