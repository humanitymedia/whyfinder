<x-admin-layout>
    <x-slot name="header">Settings</x-slot>

    <div class="max-w-4xl space-y-8">
        {{-- Stripe API Settings --}}
        <form method="POST" action="{{ route('admin.settings.stripe.update') }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-brand-dark">Stripe API Credentials</h3>
                        <p class="text-sm text-brand-gray mt-1">Configure your Stripe payment gateway credentials.</p>
                    </div>
                    <div class="flex items-center gap-3" x-data="{ mode: '{{ $stripe['mode'] }}' }">
                        <span class="text-sm font-medium" :class="mode === 'test' ? 'text-amber-600' : 'text-brand-gray'">Test</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="stripe_mode_toggle" class="sr-only peer"
                                   :checked="mode === 'live'"
                                   @change="mode = $event.target.checked ? 'live' : 'test'">
                            <input type="hidden" name="stripe_mode" :value="mode">
                            <div class="w-11 h-6 bg-amber-400 peer-checked:bg-green-500 rounded-full transition-colors after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                        </label>
                        <span class="text-sm font-medium" :class="mode === 'live' ? 'text-green-600' : 'text-brand-gray'">Live</span>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Test Credentials --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-brand-dark flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            Test Mode Credentials
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Publishable Key</label>
                                <input type="text" name="test_publishable_key" value="{{ $stripe['test_publishable_key'] }}"
                                       placeholder="pk_test_..."
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors font-mono">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Secret Key</label>
                                <input type="password" name="test_secret_key" value="{{ $stripe['test_secret_key'] }}"
                                       placeholder="sk_test_..."
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors font-mono">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Webhook Secret</label>
                                <input type="password" name="test_webhook_secret" value="{{ $stripe['test_webhook_secret'] }}"
                                       placeholder="whsec_..."
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors font-mono">
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Live Credentials --}}
                    <div class="space-y-4">
                        <h4 class="text-sm font-semibold text-brand-dark flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Live Mode Credentials
                        </h4>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Publishable Key</label>
                                <input type="text" name="live_publishable_key" value="{{ $stripe['live_publishable_key'] }}"
                                       placeholder="pk_live_..."
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors font-mono">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Secret Key</label>
                                <input type="password" name="live_secret_key" value="{{ $stripe['live_secret_key'] }}"
                                       placeholder="sk_live_..."
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors font-mono">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-brand-gray mb-1">Webhook Secret</label>
                                <input type="password" name="live_webhook_secret" value="{{ $stripe['live_webhook_secret'] }}"
                                       placeholder="whsec_..."
                                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors font-mono">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Save Stripe Settings
                    </button>
                </div>
            </div>
        </form>

        {{-- Platform Fee Settings --}}
        <form method="POST" action="{{ route('admin.settings.payments.update') }}">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-semibold text-brand-dark">Payment Settings</h3>
                    <p class="text-sm text-brand-gray mt-1">Configure platform fees and payment behavior.</p>
                </div>

                <div class="p-6">
                    <div class="max-w-xs">
                        <label class="block text-sm font-medium text-brand-gray mb-1">Global Platform Fee (%)</label>
                        <div class="relative">
                            <input type="number" name="platform_fee_percent" value="{{ $platformFeePercent }}"
                                   min="0" max="100" step="0.01"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-brand-red/20 focus:border-brand-red transition-colors pr-8">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-gray text-sm">%</span>
                        </div>
                        <p class="text-xs text-brand-gray mt-2">This is the default fee taken from each transaction. Individual instructors can have custom overrides.</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="px-6 py-2.5 bg-brand-red text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Save Payment Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-admin-layout>
