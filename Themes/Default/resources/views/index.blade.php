<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ apply_filters('botcms_homepage_title', 'BotCMS Site Frontend') }}</title>
    <!-- Tailwind Play CDN for Default Theme -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="h-full flex flex-col justify-between bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black font-sans">
    
    <!-- Top Nav -->
    <header class="border-b border-slate-800 bg-slate-950/60 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <span class="text-xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                BotCMS Theme: Default
            </span>
            <div class="flex items-center space-x-4">
                <span class="text-xs px-2.5 py-0.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 font-mono">Tailwind CSS</span>
                <a href="{{ route('login') }}" class="text-xs font-semibold px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-500 shadow-md shadow-blue-600/20 transition-all">
                    Admin Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-6 py-16 flex-grow flex flex-col justify-center text-center space-y-8">
        <div class="space-y-4">
            <h1 class="text-5xl font-extrabold tracking-tight text-white sm:text-6xl">
                {!! apply_filters('botcms_homepage_welcome_text', 'Welcome to BotCMS Platform!') !!}
            </h1>
            <p class="text-slate-400 text-lg max-w-xl mx-auto">
                This frontend is rendered dynamically using the <strong class="text-blue-400 font-semibold">Default Theme</strong> built on <strong class="text-blue-400 font-semibold">Tailwind CSS</strong>.
            </p>
        </div>

        <div class="flex justify-center gap-4">
            <div class="glass p-6 rounded-2xl max-w-sm text-left">
                <span class="text-xs text-indigo-400 font-mono font-semibold block mb-2">Dynamic Styling Demonstration</span>
                <p class="text-xs text-slate-400 leading-relaxed">
                    You can switch this entire page layout to Bootstrap by logging into the admin settings, changing the theme to <strong>BootstrapDemo</strong>, and reloading.
                </p>
            </div>
            
            <div class="glass p-6 rounded-2xl max-w-sm text-left">
                <span class="text-xs text-pink-400 font-mono font-semibold block mb-2">Secure Hook Execution</span>
                <p class="text-xs text-slate-400 leading-relaxed">
                    The title and headers on this page are wrapped in WordPress-style filter hooks: <code class="text-pink-400 font-mono">apply_filters()</code>.
                </p>
            </div>
        </div>

        <!-- Render hook output for actions -->
        @php do_action('botcms_homepage_content_footer'); @endphp
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950/40 py-8">
        <div class="max-w-7xl mx-auto px-6 text-center text-xs text-slate-500 space-y-2">
            <p>&copy; {{ date('Y') }} BotCMS Platform. Created with Laravel and Modern Architecture.</p>
            <p>Active Visual Theme: <span class="text-slate-400 font-bold">Default</span> | Active Database Driver: <span class="text-slate-400 font-mono">SQLite</span></p>
        </div>
    </footer>
</body>
</html>
