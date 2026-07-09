@extends('dashboard::layout')

@section('title', 'Payment Gateways Settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-100 font-sans">Payment Gateways Settings</h1>
        <p class="text-slate-400 text-sm">Configure checkout payment integrations for your BotCommerce storefront.</p>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-sans">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Sidebar Navigation Info Box -->
        <div class="space-y-4">
            <div class="glass p-5 rounded-2xl border border-slate-800 bg-slate-900/10">
                <h3 class="text-sm font-bold text-slate-200">Stripe Integration</h3>
                <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                    Connect your checkout flow directly to Stripe to receive debit/credit payments in real-time.
                </p>
                <div class="mt-4 p-3 rounded-lg bg-blue-500/5 border border-blue-500/10 text-[10px] text-blue-400 leading-normal">
                    💡 <strong>Tip:</strong> Get your Keys from your Stripe Dashboard Settings &gt; Developers &gt; API keys.
                </div>
            </div>
        </div>

        <!-- Settings Form -->
        <div class="md:col-span-2">
            <form action="{{ route('admin.botcommerce.gateways.save') }}" method="POST" class="glass p-6 rounded-2xl border border-slate-800 bg-slate-900/20 space-y-6">
                @csrf

                <!-- Gateway Mode -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-350 uppercase tracking-wider">Transaction Mode</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center justify-between p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-900/30 cursor-pointer">
                            <span class="text-sm text-slate-300">Test / Sandbox</span>
                            <input type="radio" name="stripe_mode" value="test" {{ $stripeMode === 'test' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-800 bg-slate-950">
                        </label>
                        <label class="flex items-center justify-between p-3 rounded-xl border border-slate-800 bg-slate-950/40 hover:bg-slate-900/30 cursor-pointer">
                            <span class="text-sm text-slate-300">Live / Production</span>
                            <input type="radio" name="stripe_mode" value="live" {{ $stripeMode === 'live' ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-800 bg-slate-950">
                        </label>
                    </div>
                </div>

                <!-- Publishable Key -->
                <div class="space-y-1.5">
                    <label for="stripe_publishable_key" class="block text-xs font-bold text-slate-350 uppercase tracking-wider">Stripe Publishable Key</label>
                    <input type="text" id="stripe_publishable_key" name="stripe_publishable_key" value="{{ $stripePublishable }}" placeholder="pk_test_..." 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950/40 text-slate-200 placeholder-slate-600 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                </div>

                <!-- Secret Key -->
                <div class="space-y-1.5">
                    <label for="stripe_secret_key" class="block text-xs font-bold text-slate-350 uppercase tracking-wider">Stripe Secret Key</label>
                    <input type="password" id="stripe_secret_key" name="stripe_secret_key" value="{{ $stripeSecret }}" placeholder="sk_test_..." 
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950/40 text-slate-200 placeholder-slate-600 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-mono">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm shadow shadow-blue-600/10 hover:shadow-blue-500/20 transition-all">
                        Save Gateway Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
