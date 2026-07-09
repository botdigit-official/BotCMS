@extends('dashboard::layout')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Top Greeting -->
    <div>
        <h1 class="text-2xl font-bold text-slate-100">Dashboard Overview</h1>
        <p class="text-slate-400 text-sm">Welcome to BotCMS. Manage your modular, multi-site content platform.</p>
    </div>

    <!-- Quick stats grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Posts card -->
        <div class="glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Posts</span>
                <span class="text-3xl font-extrabold text-blue-400">{{ $postsCount }}</span>
            </div>
            <div class="p-3 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6m-6 4h5"/>
                </svg>
            </div>
        </div>

        <!-- Pages card -->
        <div class="glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Pages</span>
                <span class="text-3xl font-extrabold text-emerald-400">{{ $pagesCount }}</span>
            </div>
            <div class="p-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>

        <!-- Users card -->
        <div class="glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Members</span>
                <span class="text-3xl font-extrabold text-violet-400">{{ $usersCount }}</span>
            </div>
            <div class="p-3 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>

        <!-- Active Theme card -->
        <div class="glass p-5 rounded-2xl flex items-center justify-between">
            <div>
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider block">Active Theme</span>
                <span class="text-xl font-extrabold text-amber-400 truncate block max-w-[150px]">{{ $activeTheme }}</span>
            </div>
            <div class="p-3 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Plugins & Themes Detail -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Themes Section -->
        <div class="glass p-6 rounded-2xl space-y-4">
            <h2 class="text-lg font-bold text-slate-200">Installed Themes</h2>
            <div class="space-y-3">
                @foreach ($themes as $theme)
                    <div class="p-4 rounded-xl border border-slate-800 bg-slate-900/50 flex items-center justify-between">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-sm">{{ $theme['name'] }}</span>
                                @if($theme['name'] === $activeTheme)
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-green-500/15 border border-green-500/25 text-green-400">Active</span>
                                @endif
                            </div>
                            <span class="text-xs text-slate-500">Styling Framework: 
                                <span class="font-semibold text-slate-400 uppercase">{{ $theme['framework'] }}</span>
                            </span>
                        </div>
                        <span class="text-xs text-slate-500">v{{ $theme['version'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Plugins Section -->
        <div class="glass p-6 rounded-2xl space-y-4">
            <h2 class="text-lg font-bold text-slate-200">Installed Plugins</h2>
            <div class="space-y-3">
                @forelse ($plugins as $plugin)
                    <div class="p-4 rounded-xl border border-slate-800 bg-slate-900/50 flex items-center justify-between">
                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="font-bold text-sm">{{ $plugin['name'] }}</span>
                                @if($plugin['enabled'])
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-blue-500/15 border border-blue-500/25 text-blue-400">Enabled</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-slate-800 text-slate-500">Disabled</span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-400">{{ $plugin['description'] }}</p>
                        </div>
                        <span class="text-xs text-slate-500">v{{ $plugin['version'] }}</span>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 text-sm">
                        No plugins installed yet. Plugins can be loaded into the <code class="bg-slate-900 px-1.5 py-0.5 rounded text-blue-400 font-mono">Plugins/</code> directory.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Secure Hook Manager Visualization -->
    <div class="glass p-6 rounded-2xl space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-200">Active Hooks Engine Debugger</h2>
            <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 font-mono">Secure Action/Filter API</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Actions -->
            <div class="space-y-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Registered Action Hooks</h3>
                @forelse($registeredActions as $hook => $priorities)
                    <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <code class="text-xs text-pink-400 font-mono">{{ $hook }}</code>
                            <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 rounded">{{ count($priorities, COUNT_RECURSIVE) - count($priorities) }} listener(s)</span>
                        </div>
                        <div class="space-y-1">
                            @foreach($priorities as $priority => $listeners)
                                @foreach($listeners as $listener)
                                    <div class="flex items-center justify-between text-xs text-slate-500 pl-3 border-l border-slate-700">
                                        <span class="font-mono">Priority: {{ $priority }}</span>
                                        <span class="text-slate-400">
                                            @if(is_string($listener['callback']))
                                                {{ $listener['callback'] }}
                                            @elseif(is_array($listener['callback']))
                                                {{ is_string($listener['callback'][0]) ? $listener['callback'][0] : get_class($listener['callback'][0]) }}@&gt;{{ $listener['callback'][1] }}
                                            @else
                                                Closure/Callback
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-slate-950/30 text-center rounded-xl text-xs text-slate-500 border border-dashed border-slate-800">
                        No actions registered yet.
                    </div>
                @endforelse
            </div>

            <!-- Filters -->
            <div class="space-y-3">
                <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400">Registered Filter Hooks</h3>
                @forelse($registeredFilters as $hook => $priorities)
                    <div class="p-3 bg-slate-950/60 rounded-xl border border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <code class="text-xs text-teal-400 font-mono">{{ $hook }}</code>
                            <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 rounded">{{ count($priorities, COUNT_RECURSIVE) - count($priorities) }} listener(s)</span>
                        </div>
                        <div class="space-y-1">
                            @foreach($priorities as $priority => $listeners)
                                @foreach($listeners as $listener)
                                    <div class="flex items-center justify-between text-xs text-slate-500 pl-3 border-l border-slate-700">
                                        <span class="font-mono">Priority: {{ $priority }}</span>
                                        <span class="text-slate-400">
                                            @if(is_string($listener['callback']))
                                                {{ $listener['callback'] }}
                                            @elseif(is_array($listener['callback']))
                                                {{ is_string($listener['callback'][0]) ? $listener['callback'][0] : get_class($listener['callback'][0]) }}@&gt;{{ $listener['callback'][1] }}
                                            @else
                                                Closure/Callback
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="p-4 bg-slate-950/30 text-center rounded-xl text-xs text-slate-500 border border-dashed border-slate-800">
                        No filters registered yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
