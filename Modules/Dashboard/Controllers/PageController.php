<?php

namespace Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index(Request $request)
    {
        $siteId = $request->get('current_site_id');
        $pages = Post::where('site_id', $siteId)
            ->where('type', 'page')
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard::pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return view('dashboard::pages.edit', [
            'page' => new Post([
                'status' => 'draft'
            ])
        ]);
    }

    /**
     * Store a newly created page in database.
     */
    public function store(Request $request)
    {
        $siteId = $request->get('current_site_id');

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
        ]);

        $slug = $request->input('slug') ? Str::slug($request->input('slug')) : Str::slug($request->input('title'));

        // Ensure unique slug per site + type
        $slugCount = Post::where('site_id', $siteId)
            ->where('type', 'page')
            ->where('slug', $slug)
            ->count();

        if ($slugCount > 0) {
            $slug .= '-' . (Post::max('id') + 1);
        }

        Post::create([
            'site_id' => $siteId,
            'user_id' => Auth::id(),
            'type' => 'page',
            'title' => $request->input('title'),
            'slug' => $slug,
            'content' => $request->input('content'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('admin.pages')->with('success', 'Page created successfully.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit(Request $request, int $id)
    {
        $siteId = $request->get('current_site_id');
        $page = Post::where('site_id', $siteId)->where('type', 'page')->findOrFail($id);

        return view('dashboard::pages.edit', compact('page'));
    }

    /**
     * Update the specified page in database.
     */
    public function update(Request $request, int $id)
    {
        $siteId = $request->get('current_site_id');
        $page = Post::where('site_id', $siteId)->where('type', 'page')->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
        ]);

        $slug = Str::slug($request->input('slug'));

        // Ensure unique slug (excluding current page ID)
        $slugCount = Post::where('site_id', $siteId)
            ->where('type', 'page')
            ->where('slug', $slug)
            ->where('id', '!=', $id)
            ->count();

        if ($slugCount > 0) {
            $slug .= '-' . $id;
        }

        $page->update([
            'title' => $request->input('title'),
            'slug' => $slug,
            'content' => $request->input('content'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('admin.pages')->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified page from database.
     */
    public function destroy(Request $request, int $id)
    {
        $siteId = $request->get('current_site_id');
        $page = Post::where('site_id', $siteId)->where('type', 'page')->findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages')->with('success', 'Page deleted successfully.');
    }
}
