@extends('dashboard::layout')

@section('title', 'Theme Management')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">Visual Themes</h1>
            <p class="text-slate-400 text-sm">Choose and activate visual themes. Install custom themes directly via ZIP upload.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Installed Themes Grid -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-semibold text-slate-350">Installed Themes</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($themes as $theme)
                    @php 
                        $isActive = ($theme['name'] === $activeTheme);
                        $isTailwind = (strtolower($theme['framework']) === 'tailwind');
                    @endphp
                    <div class="glass rounded-2xl overflow-hidden flex flex-col justify-between border {{ $isActive ? 'border-blue-500/30 shadow-lg shadow-blue-500/5' : 'border-slate-800' }}">
                        <!-- Theme Screenshot/Accent Header -->
                        <div class="h-32 bg-gradient-to-br {{ $isTailwind ? 'from-blue-600/40 to-indigo-900/40' : 'from-teal-600/40 to-cyan-900/40' }} flex items-center justify-center relative">
                            <span class="text-3xl font-extrabold text-white/10 uppercase font-mono">{{ $theme['name'] }}</span>
                            @if ($isActive)
                                <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] bg-green-500/10 border border-green-500/20 text-green-400 font-semibold uppercase">Active Theme</span>
                            @endif
                        </div>
                        
                        <!-- Details -->
                        <div class="p-5 flex-grow space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-100 text-base">{{ $theme['name'] }}</span>
                                <span class="text-xs text-slate-500 font-mono">v{{ $theme['version'] }}</span>
                            </div>
                            
                            <p class="text-xs text-slate-400 leading-relaxed">{{ $theme['description'] ?? 'No description provided.' }}</p>
                            
                            <div class="pt-2 flex items-center justify-between text-xs text-slate-500">
                                <span>CSS Framework:</span>
                                <span class="px-2 py-0.5 rounded font-bold font-mono uppercase {{ $isTailwind ? 'bg-blue-500/10 text-blue-400' : 'bg-teal-500/10 text-teal-400' }}">
                                    {{ $theme['framework'] }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-5 pt-0">
                            @if ($isActive)
                                <button disabled class="w-full py-2 bg-slate-900 border border-slate-850 text-slate-500 font-semibold rounded-lg text-xs cursor-not-allowed">
                                    Currently Active
                                </button>
                            @else
                                <form action="{{ route('admin.themes.activate', $theme['name']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold rounded-lg text-xs transition-all shadow-md shadow-blue-600/10">
                                        Activate Theme
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ZIP Uploader Side Box -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-slate-350">Add New Theme</h2>
            
            <div class="glass p-6 rounded-2xl space-y-4">
                <div class="text-xs text-slate-400 leading-relaxed">
                    Upload a theme folder in **.ZIP** format. It will be extracted dynamically. Make sure the ZIP contains a valid <code class="text-indigo-400 font-mono">theme.json</code> file in its root.
                </div>
                
                <form action="{{ route('admin.themes.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wide">Select ZIP Archive</label>
                        <input type="file" name="theme_zip" accept=".zip" required
                               class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:border-slate-800 hover:file:bg-slate-850 cursor-pointer">
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
                        Install Theme ZIP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
