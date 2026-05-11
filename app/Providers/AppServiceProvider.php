<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Override Stripe config from database settings (with .env fallback)
        if (Schema::hasTable('settings')) {
            try {
                $mode = Setting::get('stripe_mode', 'test');
                $prefix = "stripe_{$mode}";

                $key = Setting::get("{$prefix}_publishable_key");
                $secret = Setting::get("{$prefix}_secret_key");
                $webhook = Setting::get("{$prefix}_webhook_secret");

                if ($key) {
                    config(['cashier.key' => $key]);
                }
                if ($secret) {
                    config(['cashier.secret' => $secret]);
                }
                if ($webhook) {
                    config(['services.stripe.webhook.secret' => $webhook]);
                }
            } catch (\Exception) {
                // Fall back to .env values if settings table query fails
            }
        }
    }
}
