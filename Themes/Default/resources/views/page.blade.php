<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100 font-sans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title }} - {{ $site->name }}</title>
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
    <header class="border-b border-slate-800 bg-slate-950/60 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="text-xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                {{ $site->name }}
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 font-mono">Tailwind Theme</span>
                <a href="{{ route('login') }}" class="text-xs font-semibold px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 shadow-md shadow-blue-600/20 transition-all">
                    Admin Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="max-w-3xl mx-auto px-6 py-16 flex-grow w-full space-y-6">
        <div class="space-y-4">
            <h1 class="text-4xl font-extrabold tracking-tight text-white border-b border-slate-800 pb-4">
                {{ $page->title }}
            </h1>
        </div>

        <!-- Rendered Page Content -->
        <article class="prose prose-invert max-w-none text-slate-300 leading-relaxed space-y-4">
            {!! $page->content !!}
        </article>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950/40 py-6">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} {{ $site->name }}. Rendered using Default Theme.</p>
        </div>
    </footer>
</body>
</html>
