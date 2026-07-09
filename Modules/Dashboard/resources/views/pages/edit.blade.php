@extends('dashboard::layout')

@section('title', $page->exists ? 'Edit Page' : 'Create Page')

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">{{ $page->exists ? 'Edit Page: ' . $page->title : 'Create New Page' }}</h1>
            <p class="text-slate-400 text-sm">Design page metadata, database content, and configure visibility status.</p>
        </div>
        <a href="{{ route('admin.pages') }}" class="text-xs text-slate-400 hover:text-slate-200">
            &larr; Back to list
        </a>
    </div>

    <!-- Alert Box: Code-First & AI Helper Guide -->
    <div class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-xs text-indigo-300 leading-relaxed space-y-2">
        <div class="font-bold flex items-center space-x-1.5">
            <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <span>Developer &amp; AI Code-First Feature</span>
        </div>
        <p>
            You can override this database page with actual code! Simply create a file named <code class="bg-indigo-950 px-1.5 py-0.5 rounded text-indigo-400 font-mono">page-{{ $page->slug ?: 'your-slug' }}.blade.php</code> inside your active theme's view folder (<code class="bg-indigo-950 px-1.5 py-0.5 rounded text-indigo-400 font-mono">Themes/{active}/resources/views/</code>). 
        </p>
        <p class="text-slate-400 text-[11px]">
            This makes it incredibly easy for coding AIs or developers to build high-performance custom layouts with advanced CSS and JS widgets directly in the repository while keeping admin editors completely optional.
        </p>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ $page->exists ? route('admin.pages.update', $page->id) : route('admin.pages.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="glass p-6 rounded-2xl space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-300">Page Title</label>
                    <input type="text" id="title" name="title" required
                           value="{{ old('title', $page->title) }}"
                           class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-300">Slug / URL Path</label>
                    <input type="text" id="slug" name="slug" 
                           placeholder="e.g. about-us (leave empty to generate from title)"
                           value="{{ old('slug', $page->slug) }}"
                           class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
            </div>

            <!-- Content Area -->
            <div>
                <label for="content" class="block text-sm font-medium text-slate-300">Page Content (HTML/Blade support)</label>
                <textarea id="content" name="content" rows="12"
                          placeholder="Write your HTML markup or page content here..."
                          class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-4 py-3 text-slate-200 placeholder-slate-500 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('content', $page->content) }}</textarea>
            </div>

            <!-- Status & Save -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-slate-800">
                <div class="w-full sm:w-1/3">
                    <label for="status" class="block text-sm font-medium text-slate-300">Publish Status</label>
                    <select id="status" name="status" 
                            class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $page->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.pages') }}" class="py-2.5 px-6 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-400">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="py-2.5 px-6 border border-transparent rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/25 transition-all">
                        {{ $page->exists ? 'Update Page Content' : 'Save New Page' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
