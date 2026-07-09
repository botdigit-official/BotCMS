<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - BotCMS</title>
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
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
</head>
<body class="h-full flex overflow-hidden bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">
    <!-- Sidebar -->
    <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 border-r border-slate-800 bg-slate-950/80 backdrop-blur-md z-25">
        <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
            <div class="flex items-center flex-shrink-0 px-6 mb-6">
                <span class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-400">
                    BotCMS Panel
                </span>
            </div>
            <nav class="mt-5 flex-1 px-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-600/10 border border-blue-500/20 text-blue-400 font-semibold' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }} group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                @if(auth()->user()->current_role && in_array(auth()->user()->current_role->slug, ['super_admin', 'admin', 'editor']))
                <a href="{{ route('admin.pages') }}" 
                   class="{{ request()->routeIs('admin.pages*') ? 'bg-blue-600/10 border border-blue-500/20 text-blue-400 font-semibold' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }} group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Pages
                </a>
                @endif

                @php $cpts = app('botcms.posttypes')->all(); @endphp
                @foreach($cpts as $name => $options)
                    @if(auth()->user()->current_role && in_array(auth()->user()->current_role->slug, ['super_admin', 'admin', 'editor']))
                    <a href="{{ route('admin.cpt', $name) }}" 
                       class="{{ request()->is("admin/cpt/{$name}*") ? 'bg-blue-600/10 border border-blue-500/20 text-blue-400 font-semibold' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }} group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150 font-sans">
                        <svg class="mr-3 h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            @if($options['icon'] === 'briefcase')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            @elseif($options['icon'] === 'chat')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            @endif
                        </svg>
                        {{ $options['label'] }}
                    </a>
                    @endif
                @endforeach

                @if(auth()->user()->current_role && in_array(auth()->user()->current_role->slug, ['super_admin', 'admin']))
                <a href="{{ route('admin.themes') }}" 
                   class="{{ request()->routeIs('admin.themes') ? 'bg-blue-600/10 border border-blue-500/20 text-blue-400 font-semibold' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }} group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    Themes
                </a>

                <a href="{{ route('admin.plugins') }}" 
                   class="{{ request()->routeIs('admin.plugins') ? 'bg-blue-600/10 border border-blue-500/20 text-blue-400 font-semibold' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }} group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    </svg>
                    Plugins
                </a>

                <a href="{{ route('admin.settings') }}" 
                   class="{{ request()->routeIs('admin.settings') ? 'bg-blue-600/10 border border-blue-500/20 text-blue-400 font-semibold' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }} group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Settings
                </a>
                @endif

                <a href="/" target="_blank" 
                   class="text-slate-400 hover:bg-slate-900 hover:text-slate-200 group flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-150">
                    <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Visit Website
                </a>
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="md:pl-64 flex flex-col flex-1 w-0">
        <!-- Top Header -->
        <div class="relative z-10 flex-shrink-0 flex h-16 bg-slate-950/40 backdrop-blur-md border-b border-slate-800">
            <div class="flex-grow flex items-center justify-between px-6">
                <div>
                    <!-- Breadcrumbs or site indicator -->
                    <span class="text-sm font-medium text-slate-400">Site:</span>
                    <span class="text-sm font-semibold text-blue-400">{{ request()->get('current_site')->name }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <div class="text-sm font-medium text-slate-200">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500 font-mono">{{ auth()->user()->current_role->name ?? 'User' }}</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-lg bg-slate-900 hover:bg-rose-500/10 border border-slate-800 hover:border-rose-500/20 text-slate-400 hover:text-rose-400 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Body Scrollable container -->
        <main class="flex-grow overflow-y-auto focus:outline-none p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
