<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $stripeMode = Setting::get('stripe_mode', 'test');

        $stripe = [
            'mode' => $stripeMode,
            'test_publishable_key' => Setting::get('stripe_test_publishable_key', ''),
            'test_secret_key' => Setting::get('stripe_test_secret_key', ''),
            'test_webhook_secret' => Setting::get('stripe_test_webhook_secret', ''),
            'live_publishable_key' => Setting::get('stripe_live_publishable_key', ''),
            'live_secret_key' => Setting::get('stripe_live_secret_key', ''),
            'live_webhook_secret' => Setting::get('stripe_live_webhook_secret', ''),
        ];

        $platformFeePercent = Setting::get('platform_fee_percent', 30);

        return view('admin.settings.index', compact('stripe', 'platformFeePercent'));
    }

    public function updateStripe(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'stripe_mode' => ['required', 'in:test,live'],
            'test_publishable_key' => ['nullable', 'string', 'max:255'],
            'test_secret_key' => ['nullable', 'string', 'max:255'],
            'test_webhook_secret' => ['nullable', 'string', 'max:255'],
            'live_publishable_key' => ['nullable', 'string', 'max:255'],
            'live_secret_key' => ['nullable', 'string', 'max:255'],
            'live_webhook_secret' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::set('stripe_mode', $validated['stripe_mode'], 'string', 'stripe');
        Setting::set('stripe_test_publishable_key', $validated['test_publishable_key'], 'encrypted', 'stripe');
        Setting::set('stripe_test_secret_key', $validated['test_secret_key'], 'encrypted', 'stripe');
        Setting::set('stripe_test_webhook_secret', $validated['test_webhook_secret'], 'encrypted', 'stripe');
        Setting::set('stripe_live_publishable_key', $validated['live_publishable_key'], 'encrypted', 'stripe');
        Setting::set('stripe_live_secret_key', $validated['live_secret_key'], 'encrypted', 'stripe');
        Setting::set('stripe_live_webhook_secret', $validated['live_webhook_secret'], 'encrypted', 'stripe');

        return back()->with('success', 'Stripe settings updated successfully.');
    }

    public function updatePayments(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_fee_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        Setting::set('platform_fee_percent', $validated['platform_fee_percent'], 'integer', 'payments');

        return back()->with('success', 'Payment settings updated successfully.');
    }
}
