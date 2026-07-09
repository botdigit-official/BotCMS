<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100 font-sans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} - {{ $site->name ?? 'BotCMS' }}</title>
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
                <a href="{{ route('login') }}" class="text-xs font-semibold px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 shadow-md shadow-blue-600/20 transition-all">
                    Admin Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-16 flex-grow w-full space-y-8">
        <div>
            <a href="/shop" class="text-xs text-blue-400 hover:underline flex items-center space-x-1">
                <span>&larr; Back to Catalog</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Product Information -->
            <div class="space-y-6">
                <div class="space-y-2">
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-slate-900 border border-slate-800 text-slate-400 font-mono">
                        SKU: {{ $product->sku ?: 'N/A' }}
                    </span>
                    <h1 class="text-3xl font-extrabold text-white leading-tight">
                        {{ $post->title }}
                    </h1>
                </div>

                <div class="prose prose-invert text-slate-300 text-sm leading-relaxed">
                    {!! $post->content !!}
                </div>
            </div>

            <!-- Checkout / Specs Box -->
            <div>
                <div class="glass p-8 rounded-2xl space-y-6">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-800">
                        <span class="text-slate-450 text-xs uppercase tracking-wider font-semibold">Product Price</span>
                        <span class="text-3xl font-black text-emerald-400">${{ number_format($product->price, 2) }}</span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Inventory Status</span>
                            @if ($product->stock_quantity > 0)
                                <span class="text-green-400 font-semibold">In Stock ({{ $product->stock_quantity }} units)</span>
                            @else
                                <span class="text-rose-400 font-semibold">Out of Stock</span>
                            @endif
                        </div>

                        <div class="flex justify-between text-xs">
                            <span class="text-slate-400">Shipping</span>
                            <span class="text-slate-200">Calculated at checkout</span>
                        </div>
                    </div>

                    <button class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold tracking-wide rounded-xl shadow-lg shadow-blue-500/25 transition-all">
                        Add To Cart
                    </button>
                    
                    <p class="text-center text-[10px] text-slate-500 leading-normal">
                        This is a functional reference demo representing E-Commerce integration in BotCMS.
                    </p>
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
