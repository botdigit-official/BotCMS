@extends('dashboard::layout')

@section('title', $post->exists ? "Edit {$cpt['singular_label']}" : "Create {$cpt['singular_label']}")

@section('content')
<div class="space-y-6 max-w-4xl">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">
                {{ $post->exists ? "Edit {$cpt['singular_label']}: " . $post->title : "Create {$cpt['singular_label']}" }}
            </h1>
            <p class="text-slate-400 text-sm">Fill in the structured fields. Customized attributes are saved cleanly in native JSON format.</p>
        </div>
        <a href="{{ route('admin.cpt', $type) }}" class="text-xs text-slate-400 hover:text-slate-200">
            &larr; Back to list
        </a>
    </div>

    <!-- Alert Box: CPT Custom Code Layouts -->
    <div class="p-4 rounded-xl bg-indigo-500/10 border border-indigo-500/20 text-xs text-indigo-300 leading-relaxed space-y-2">
        <div class="font-bold flex items-center space-x-1.5">
            <svg class="h-4 w-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
            </svg>
            <span>Dynamic Code Templates Guide</span>
        </div>
        <p>
            You can override this post type views inside your code repo! Create a file named <code class="bg-indigo-950 px-1.5 py-0.5 rounded text-indigo-400 font-mono">single-{{ $type }}.blade.php</code> (or a directory listing file <code class="bg-indigo-950 px-1.5 py-0.5 rounded text-indigo-400 font-mono">archive-{{ $type }}.blade.php</code>) inside your active theme folder (<code class="bg-indigo-950 px-1.5 py-0.5 rounded text-indigo-400 font-mono">Themes/{active}/resources/views/</code>). 
        </p>
        <p class="text-slate-400 text-[11px]">
            AIs and developers can easily build custom cards and styles for this post type without polluting the standard page layouts.
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
    <form action="{{ $post->exists ? route('admin.cpt.update', [$type, $post->id]) : route('admin.cpt.store', $type) }}" method="POST" class="space-y-6">
        @csrf

        <div class="glass p-6 rounded-2xl space-y-6">
            
            <!-- Standard Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-slate-300">Title</label>
                <input type="text" id="title" name="title" required
                       value="{{ old('title', $post->title) }}"
                       class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            </div>

            <!-- Standard Content (only if supported) -->
            @if (in_array('editor', $cpt['supports']))
                <div>
                    <label for="content" class="block text-sm font-medium text-slate-300">Description / Main Content</label>
                    <textarea id="content" name="content" rows="8"
                              class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-4 py-3 text-slate-200 placeholder-slate-500 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('content', $post->content) }}</textarea>
                </div>
            @endif

            <!-- Custom Schema Fields (Rendered Dynamically) -->
            @if (!empty($cpt['fields']))
                <div class="border-t border-slate-800 pt-6 space-y-4">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-4">Custom Attributes</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($cpt['fields'] as $key => $field)
                            <div>
                                <label for="field_{{ $key }}" class="block text-sm font-medium text-slate-300">{{ $field['label'] }}</label>
                                
                                @if ($field['type'] === 'textarea')
                                    <textarea id="field_{{ $key }}" name="meta[{{ $key }}]" rows="4"
                                              placeholder="{{ $field['placeholder'] ?? '' }}"
                                              class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">{{ old("meta.{$key}", $post->meta[$key] ?? '') }}</textarea>
                                @elseif ($field['type'] === 'date')
                                    <input type="date" id="field_{{ $key }}" name="meta[{{ $key }}]"
                                           value="{{ old("meta.{$key}", $post->meta[$key] ?? '') }}"
                                           class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm col-span-2">
                                @else
                                    <input type="{{ $field['type'] }}" id="field_{{ $key }}" name="meta[{{ $key }}]"
                                           placeholder="{{ $field['placeholder'] ?? '' }}"
                                           value="{{ old("meta.{$key}", $post->meta[$key] ?? '') }}"
                                           class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Status & Save -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 border-t border-slate-800">
                <div class="w-full sm:w-1/3">
                    <label for="status" class="block text-sm font-medium text-slate-300">Publish Status</label>
                    <select id="status" name="status" 
                            class="mt-1 block w-full rounded-lg bg-slate-900 border border-slate-700 px-3 py-2 text-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $post->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 w-full sm:w-auto">
                    <a href="{{ route('admin.cpt', $type) }}" class="py-2.5 px-6 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-400">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="py-2.5 px-6 border border-transparent rounded-lg text-xs font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-500/25 transition-all">
                        {{ $post->exists ? 'Update Content' : 'Save Entry' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
