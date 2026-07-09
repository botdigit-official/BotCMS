<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BotCMS</title>
    <!-- Tailwind CSS Play CDN for zero-config premium design -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f5ff',
                            100: '#e0ebff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="h-full flex flex-col justify-center relative overflow-hidden bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">
    <!-- Animated background grids & glow effects -->
    <div class="absolute inset-0 z-0 opacity-30 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-500/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-indigo-500/20 blur-[120px]"></div>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md z-10 px-4">
        <!-- Logo / Title -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-400 to-violet-400">
                BotCMS
            </h1>
            <p class="mt-2 text-sm text-slate-400">
                A Laravel-powered Content Management Platform
            </p>
        </div>

        <!-- Glassmorphic Card -->
        <div class="glass py-8 px-6 shadow-2xl rounded-2xl sm:px-10">
            <h2 class="text-xl font-semibold text-slate-200 mb-6 text-center">Welcome back</h2>
            
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Email address</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                            value="{{ old('email', 'admin@botcms.local') }}"
                            class="block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            value="admin123"
                            class="block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" checked
                            class="h-4 w-4 rounded bg-slate-900 border-slate-700 text-blue-600 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-sm text-slate-300">Remember me</label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-400 hover:text-blue-300">Forgot your password?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/25 transition-all">
                        Sign in
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center text-xs text-slate-500">
                Default: <span class="text-blue-400">admin@botcms.local</span> / <span class="text-blue-400">admin123</span>
            </div>
        </div>
    </div>
</body>
</html>
