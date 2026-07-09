<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100 font-sans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout - {{ $site->name ?? 'BotCMS' }}</title>
    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">
    
    <!-- Top Header -->
    <header class="border-b border-slate-800 bg-slate-950/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="text-xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                {{ $site->name ?? 'BotCMS' }}
            </a>
            <div class="flex items-center space-x-6">
                <a href="/shop" class="text-sm font-semibold text-slate-400 hover:text-white">Shop Catalog</a>
                <a href="/cart" class="text-sm font-semibold text-slate-450 hover:text-white">Cart</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-16 flex-grow w-full space-y-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Secure Checkout</h1>
            <p class="text-slate-400 text-sm mt-1">Complete your transaction securely via Stripe Gateway.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <form class="glass p-6 rounded-2xl space-y-6 border border-slate-800 bg-slate-900/10">
                    <h3 class="text-sm font-bold text-slate-200 border-b border-slate-850 pb-3 uppercase tracking-wider">Billing &amp; Delivery</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-400 uppercase">First Name</label>
                            <input type="text" placeholder="John" class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950/40 text-slate-200 text-sm focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-400 uppercase">Last Name</label>
                            <input type="text" placeholder="Doe" class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950/40 text-slate-200 text-sm focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Email Address</label>
                        <input type="email" placeholder="john.doe@example.com" class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950/40 text-slate-200 text-sm focus:border-blue-500 transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-400 uppercase">Delivery Address</label>
                        <input type="text" placeholder="123 Developer St." class="w-full px-4 py-2.5 rounded-xl border border-slate-800 bg-slate-950/40 text-slate-200 text-sm focus:border-blue-500 transition-all">
                    </div>
                </form>

                <!-- Payment Details -->
                <div class="glass p-6 rounded-2xl space-y-6 border border-slate-800 bg-slate-900/10">
                    <h3 class="text-sm font-bold text-slate-200 border-b border-slate-850 pb-3 uppercase tracking-wider">Stripe Secure Card Element</h3>
                    
                    <div class="p-4 rounded-xl border border-slate-800 bg-slate-950/60 font-mono text-xs text-slate-400 space-y-4">
                        <div class="flex items-center space-x-3 text-blue-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="font-bold">Stripe Elements (Sandbox Active)</span>
                        </div>
                        
                        <div class="p-4 rounded-lg bg-slate-900 border border-slate-800 text-[11px] space-y-2">
                            <div class="flex justify-between">
                                <span>Card Number:</span>
                                <span class="text-slate-200">4242 4242 4242 4242</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Expiry:</span>
                                <span class="text-slate-200">12 / 28</span>
                            </div>
                            <div class="flex justify-between">
                                <span>CVC:</span>
                                <span class="text-slate-200">424</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Total -->
            <div>
                <div class="glass p-6 rounded-2xl space-y-6">
                    <h3 class="text-sm font-bold text-white border-b border-slate-800 pb-3 uppercase tracking-wider">Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Item Cost</span>
                            <span class="text-slate-200">$499.00</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Taxes</span>
                            <span class="text-slate-200">$0.00</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-white pt-2 border-t border-slate-800">
                            <span>Total Pay</span>
                            <span class="text-emerald-400 text-sm">$499.00</span>
                        </div>
                    </div>

                    <button type="button" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold tracking-wide rounded-xl shadow-lg shadow-blue-500/25 transition-all">
                        Pay Securely with Stripe
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950/40 py-6 mt-16">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} {{ $site->name ?? 'BotCMS' }}. E-Commerce Catalog Module.</p>
        </div>
    </footer>
</body>
</html>
