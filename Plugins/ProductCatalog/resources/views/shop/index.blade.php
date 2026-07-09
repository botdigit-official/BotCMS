<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100 font-sans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Catalog - {{ $site->name ?? 'BotCMS' }}</title>
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
                <a href="/shop" class="text-sm font-semibold text-blue-450 hover:text-blue-400">Shop Catalog</a>
                <a href="{{ route('login') }}" class="text-xs font-semibold px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 shadow-md shadow-blue-600/20 transition-all">
                    Admin Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow w-full space-y-8">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-white">Products Catalog</h1>
            <p class="text-slate-400 text-sm mt-1">Explore our high-performance product catalog powered by BotCMS SQL module mapping.</p>
        </div>

        <!-- Product Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($products as $product)
                <div class="glass p-6 rounded-2xl flex flex-col justify-between hover:border-blue-500/25 transition-all">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-slate-900 border border-slate-800 text-slate-400 font-mono">
                                SKU: {{ $product->sku ?: 'N/A' }}
                            </span>
                            @if ($product->is_featured)
                                <span class="text-[10px] px-2.5 py-0.5 rounded-full bg-indigo-500/10 border border-indigo-500/25 text-indigo-400 font-semibold uppercase">Featured</span>
                            @endif
                        </div>
                        
                        <h2 class="text-lg font-bold text-white leading-snug">
                            {{ $product->post->title }}
                        </h2>

                        <p class="text-slate-400 text-xs line-clamp-3 leading-relaxed">
                            {{ strip_tags($product->post->content) ?: 'No description provided.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-850 pt-4 mt-6">
                        <div>
                            <span class="text-xs text-slate-500 block">Price</span>
                            <span class="text-xl font-black text-emerald-400">${{ number_format($product->price, 2) }}</span>
                        </div>
                        <div>
                            <a href="/shop/product/{{ $product->post->slug }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600/10 border border-blue-500/20 text-blue-400 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-semibold tracking-wide transition-all">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center text-slate-500 text-sm">
                    No products added to the shop catalog yet. Add products in the admin panel to populate this listing!
                </div>
            @endforelse
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
