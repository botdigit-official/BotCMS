@extends('dashboard::layout')

@section('title', 'Plugin Management')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">Extension Plugins</h1>
            <p class="text-slate-400 text-sm">Toggle active features or upload custom plugins in ZIP format to extend capabilities.</p>
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
        <!-- Plugins List Table -->
        <div class="lg:col-span-2 space-y-4">
            <h2 class="text-lg font-semibold text-slate-350">Installed Plugins</h2>
            
            <div class="glass rounded-2xl overflow-hidden border border-slate-800">
                <div class="divide-y divide-slate-850">
                    @forelse ($plugins as $plugin)
                        @php $isEnabled = $plugin['enabled']; @endphp
                        <div class="p-5 flex items-start justify-between gap-4 bg-slate-900/20 {{ $isEnabled ? 'border-l-4 border-blue-500 bg-blue-500/5' : '' }}">
                            <div class="space-y-1.5 flex-grow">
                                <div class="flex items-center space-x-2">
                                    <span class="font-bold text-slate-100 text-sm">{{ $plugin['name'] }}</span>
                                    <span class="text-[10px] text-slate-500 font-mono">v{{ $plugin['version'] }}</span>
                                </div>
                                <p class="text-xs text-slate-400 leading-relaxed">{{ $plugin['description'] }}</p>
                                
                                @if (isset($plugin['author']))
                                    <div class="text-[10px] text-slate-500">
                                        By <span class="text-slate-400 font-semibold">{{ $plugin['author'] }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Toggle Button -->
                            <div class="flex-shrink-0">
                                <form action="{{ route('admin.plugins.toggle', $plugin['name']) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-1.5 rounded-lg font-bold text-xs shadow transition-all {{ $isEnabled ? 'bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 text-rose-400' : 'bg-blue-600 hover:bg-blue-500 text-white shadow-blue-600/10' }}">
                                        {{ $isEnabled ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 text-sm">
                            No plugins installed. Put folder in root directory <code class="bg-slate-950 px-1.5 py-0.5 rounded text-blue-400">Plugins/</code> or upload ZIP.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ZIP Uploader Side Box -->
        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-slate-350">Add New Plugin</h2>
            
            <div class="glass p-6 rounded-2xl space-y-4">
                <div class="text-xs text-slate-400 leading-relaxed">
                    Upload a plugin package in **.ZIP** format. It will be extracted dynamically. Make sure the ZIP contains a valid <code class="text-indigo-400 font-mono">plugin.json</code> file in its root.
                </div>
                
                <form action="{{ route('admin.plugins.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 mb-2 uppercase tracking-wide">Select ZIP Archive</label>
                        <input type="file" name="plugin_zip" accept=".zip" required
                               class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-slate-300 file:border-slate-800 hover:file:bg-slate-850 cursor-pointer">
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs shadow-md shadow-indigo-600/25 transition-all">
                        Install Plugin ZIP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
