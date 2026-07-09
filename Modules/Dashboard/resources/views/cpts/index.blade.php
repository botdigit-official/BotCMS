@extends('dashboard::layout')

@section('title', "Manage {$cpt['label']}")

@section('content')
<div class="space-y-6">
    <!-- Top Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-100">{{ $cpt['label'] }}</h1>
            <p class="text-slate-400 text-sm">Add and manage structured {{ strtolower($cpt['label']) }} entries dynamically.</p>
        </div>
        <div>
            <a href="{{ route('admin.cpt.create', $type) }}" 
               class="inline-flex items-center justify-center py-2.5 px-4 rounded-lg bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs shadow-md shadow-blue-600/25 transition-all">
                <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add {{ $cpt['singular_label'] }}
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data List -->
    <div class="glass rounded-2xl overflow-hidden border border-slate-800">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-slate-950 border-b border-slate-800 text-slate-400 uppercase tracking-wider">
                    <th class="p-4 font-bold">Title</th>
                    
                    <!-- Dynamic Headers from Schema Fields -->
                    @foreach (array_slice($cpt['fields'], 0, 2) as $key => $field)
                        <th class="p-4 font-bold">{{ $field['label'] }}</th>
                    @endforeach
                    
                    <th class="p-4 font-bold text-center">Status</th>
                    <th class="p-4 font-bold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-850">
                @forelse ($posts as $post)
                    <tr class="hover:bg-slate-900/30">
                        <td class="p-4 font-bold text-slate-200 text-sm">
                            {{ $post->title }}
                        </td>
                        
                        <!-- Dynamic Columns data mapping from metadata JSON -->
                        @foreach (array_slice($cpt['fields'], 0, 2) as $key => $field)
                            <td class="p-4 text-slate-400">
                                {{ $post->meta[$key] ?? '—' }}
                            </td>
                        @endforeach

                        <td class="p-4 text-center">
                            @if ($post->status === 'published')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-green-500/15 border border-green-500/25 text-green-400 font-semibold uppercase">Published</span>
                            @elseif ($post->status === 'draft')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-slate-800 text-slate-400 border border-slate-700 font-semibold uppercase">Draft</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-rose-500/10 text-rose-400 border border-rose-500/20 font-semibold uppercase">Archived</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="inline-flex items-center space-x-2">
                                <a href="{{ route('admin.cpt.edit', [$type, $post->id]) }}" class="p-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:border-blue-500/30 hover:text-blue-400 transition-all">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.cpt.delete', [$type, $post->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg bg-slate-900 border border-slate-800 hover:border-rose-500/30 hover:text-rose-400 text-slate-400 hover:text-rose-400 transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500 text-sm">
                            No {{ strtolower($cpt['label']) }} entries found. Click "Add {{ $cpt['singular_label'] }}" to create one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
