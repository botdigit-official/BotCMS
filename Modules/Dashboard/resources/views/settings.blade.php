@extends('dashboard::layout')

@section('title', 'Site Settings')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h1 class="text-2xl font-bold text-slate-100">Site Settings</h1>
        <p class="text-slate-400 text-sm">Configure core settings, active visual themes, and extend capabilities using plugins.</p>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.save') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Site Identity Card -->
        <div class="glass p-6 rounded-2xl space-y-4">
            <h2 class="text-lg font-semibold text-slate-200">Site Identity</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="site_name" class="block text-sm font-medium text-slate-300">Site Name</label>
                    <input type="text" id="site_name" name="site_name" 
                           value="{{ old('site_name', $settings['site_name'] ?? 'BotCMS Platform') }}"
                           class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300">Domain Mapping</label>
                    <input type="text" disabled 
                           value="{{ request()->getHost() }}"
                           class="mt-1 block w-full rounded-lg bg-slate-950 border border-slate-800 px-3 py-2 text-slate-500 cursor-not-allowed text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                    class="py-2.5 px-6 border border-transparent rounded-lg text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/25 transition-all">
                Save Site Configurations
            </button>
        </div>
    </form>
</div>
@endsection
