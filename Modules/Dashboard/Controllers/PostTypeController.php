<?php

namespace Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Core\Facades\PostType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostTypeController extends Controller
{
    /**
     * Display listing of custom post type items.
     */
    public function index(Request $request, string $type)
    {
        if (!PostType::exists($type)) {
            abort(404, "Custom Post Type [{$type}] not registered.");
        }

        $siteId = $request->get('current_site_id');
        $cpt = PostType::get($type);
        
        $posts = Post::where('site_id', $siteId)
            ->where('type', $type)
            ->orderBy('id', 'desc')
            ->get();

        // Convert metadata JSON to array for easy view usage
        foreach ($posts as $post) {
            $post->meta = $post->metadata ?: [];
        }

        return view('dashboard::cpts.index', compact('posts', 'type', 'cpt'));
    }

    /**
     * Show form to create custom post type item.
     */
    public function create(Request $request, string $type)
    {
        if (!PostType::exists($type)) {
            abort(404);
        }

        $cpt = PostType::get($type);
        $post = new Post([
            'type' => $type,
            'status' => 'draft',
            'metadata' => []
        ]);
        $post->meta = [];

        return view('dashboard::cpts.edit', compact('post', 'type', 'cpt'));
    }

    /**
     * Store new custom post type item in database.
     */
    public function store(Request $request, string $type)
    {
        if (!PostType::exists($type)) {
            abort(404);
        }

        $siteId = $request->get('current_site_id');
        $cpt = PostType::get($type);

        // 1. Build dynamic validation rules
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'content' => 'nullable|string',
        ];

        foreach ($cpt['fields'] as $key => $field) {
            $rules["meta.{$key}"] = $field['type'] === 'date' ? 'nullable|date' : 'nullable|string';
        }

        $request->validate($rules);

        // 2. Generate slug
        $slug = Str::slug($request->input('title'));
        $slugCount = Post::where('site_id', $siteId)
            ->where('type', $type)
            ->where('slug', $slug)
            ->count();

        if ($slugCount > 0) {
            $slug .= '-' . (Post::max('id') + 1);
        }

        // 3. Save
        $post = Post::create([
            'site_id' => $siteId,
            'user_id' => Auth::id(),
            'type' => $type,
            'title' => $request->input('title'),
            'slug' => $slug,
            'content' => $request->input('content'),
            'metadata' => $request->input('meta', []),
            'status' => $request->input('status'),
        ]);

        do_action("botcms_cpt_saved_{$type}", $post, $request);

        return redirect()->route('admin.cpt', $type)->with('success', "{$cpt['singular_label']} created successfully.");
    }

    /**
     * Show form to edit custom post type item.
     */
    public function edit(Request $request, string $type, int $id)
    {
        if (!PostType::exists($type)) {
            abort(404);
        }

        $siteId = $request->get('current_site_id');
        $cpt = PostType::get($type);
        $post = Post::where('site_id', $siteId)->where('type', $type)->findOrFail($id);
        $post->meta = $post->metadata ?: [];

        return view('dashboard::cpts.edit', compact('post', 'type', 'cpt'));
    }

    /**
     * Update custom post type item.
     */
    public function update(Request $request, string $type, int $id)
    {
        if (!PostType::exists($type)) {
            abort(404);
        }

        $siteId = $request->get('current_site_id');
        $cpt = PostType::get($type);
        $post = Post::where('site_id', $siteId)->where('type', $type)->findOrFail($id);

        // 1. Validate
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'content' => 'nullable|string',
        ];

        foreach ($cpt['fields'] as $key => $field) {
            $rules["meta.{$key}"] = $field['type'] === 'date' ? 'nullable|date' : 'nullable|string';
        }

        $request->validate($rules);

        // 2. Save
        $post->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'metadata' => $request->input('meta', []),
            'status' => $request->input('status'),
        ]);

        do_action("botcms_cpt_saved_{$type}", $post, $request);

        return redirect()->route('admin.cpt', $type)->with('success', "{$cpt['singular_label']} updated successfully.");
    }

    /**
     * Delete custom post type item.
     */
    public function destroy(Request $request, string $type, int $id)
    {
        if (!PostType::exists($type)) {
            abort(404);
        }

        $siteId = $request->get('current_site_id');
        $cpt = PostType::get($type);
        $post = Post::where('site_id', $siteId)->where('type', $type)->findOrFail($id);
        $post->delete();

        return redirect()->route('admin.cpt', $type)->with('success', "{$cpt['singular_label']} deleted successfully.");
    }
}
