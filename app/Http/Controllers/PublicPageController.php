<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Core\Facades\PostType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PublicPageController extends Controller
{
    /**
     * Show a public page dynamically.
     */
    public function show(Request $request, string $slug = '')
    {
        // 1. Resolve current site by domain host
        $domain = $request->getHost();
        $site = DB::table('sites')->where('domain', $domain)->first();
        if (!$site) {
            $site = DB::table('sites')->find(1);
        }

        if (!$site || !$site->is_active) {
            abort(404, 'Site not active.');
        }

        // 2. Fetch page from DB if it exists
        $page = Post::where('site_id', $site->id)
            ->where('type', 'page')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        // 3. Resolve visual rendering hierarchy
        $activeTheme = DB::table('settings')->where('site_id', $site->id)->where('key', 'active_theme')->value('value') ?: 'Default';

        // Check template files inside active theme
        // Path matches: Themes/{ActiveTheme}/resources/views/pages/{slug}.blade.php
        $customTemplate = "pages.{$slug}";
        $customPrefixTemplate = "page-{$slug}";

        if (View::exists($customTemplate)) {
            return view($customTemplate, compact('page', 'site'));
        }

        if (View::exists($customPrefixTemplate)) {
            return view($customPrefixTemplate, compact('page', 'site'));
        }

        // If page is not in DB and no custom code template exists -> 404
        if (!$page) {
            abort(404, "Page [/{$slug}] not found.");
        }

        // Render generic theme page view if it exists
        if (View::exists('page')) {
            return view('page', compact('page', 'site'));
        }

        // System default fallback layout
        return $this->renderSystemFallbackPage($page, $site);
    }

    /**
     * Show custom post type item dynamically.
     */
    public function showPost(Request $request, string $type, string $slug)
    {
        if (!PostType::exists($type)) {
            abort(404, "Custom Post Type [{$type}] not registered.");
        }

        // Resolve current site
        $domain = $request->getHost();
        $site = DB::table('sites')->where('domain', $domain)->first();
        if (!$site) {
            $site = DB::table('sites')->find(1);
        }

        // Fetch published item from DB
        $post = Post::where('site_id', $site->id)
            ->where('type', $type)
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $post->meta = $post->metadata ?: [];

        // check theme template overrides
        $singleTemplate = "single-{$type}";
        if (View::exists($singleTemplate)) {
            return view($singleTemplate, compact('post', 'site'));
        }

        // fallback to standard theme page view
        if (View::exists('page')) {
            $page = $post;
            return view('page', compact('page', 'site'));
        }

        // System fallback
        return $this->renderSystemFallbackPage($post, $site);
    }

    /**
     * System default fallback HTML if theme doesn't define custom views.
     */
    protected function renderSystemFallbackPage($page, $site)
    {
        $content = e($page->content);
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <title>{$page->title} - {$site->name}</title>
            <script src='https://cdn.tailwindcss.com'></script>
        </head>
        <body class='bg-slate-900 text-slate-100 flex flex-col justify-between h-screen p-8'>
            <div class='max-w-2xl mx-auto'>
                <a href='/' class='text-blue-400 hover:underline'>&larr; Back Home</a>
                <h1 class='text-4xl font-extrabold my-6 border-b border-slate-700 pb-3'>{$page->title}</h1>
                <div class='prose prose-invert'>{$page->content}</div>
            </div>
        </body>
        </html>
        ";
    }
}
