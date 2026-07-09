<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100 font-sans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - {{ $site->name ?? 'BotCMS' }}</title>
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
                <a href="/cart" class="text-sm font-semibold text-blue-450 hover:text-blue-450">Cart</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-16 flex-grow w-full space-y-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Your Shopping Cart</h1>
            <p class="text-slate-400 text-sm mt-1">Review your selected items before proceeding to checkout.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <div class="glass p-6 rounded-2xl flex items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center font-bold text-white text-xs">
                            Demo Item
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-white">High-Performance Developer Hub</h3>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">SKU: DEV-HUB-01</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-extrabold text-white">$499.00</span>
                        <span class="text-[10px] text-slate-500 block">Qty: 1</span>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <div class="glass p-6 rounded-2xl space-y-6">
                    <h3 class="text-sm font-bold text-white border-b border-slate-800 pb-3 uppercase tracking-wider">Order Summary</h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Subtotal</span>
                            <span class="text-slate-200">$499.00</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Shipping</span>
                            <span class="text-emerald-400 font-semibold">Free Shipping</span>
                        </div>
                        <div class="flex justify-between text-xs font-bold text-white pt-2 border-t border-slate-800">
                            <span>Total Price</span>
                            <span class="text-emerald-400 text-sm">$499.00</span>
                        </div>
                    </div>

                    <a href="/checkout" class="block w-full py-3 text-center bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold tracking-wide rounded-xl shadow-lg shadow-blue-500/25 transition-all">
                        Proceed to Checkout
                    </a>
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
