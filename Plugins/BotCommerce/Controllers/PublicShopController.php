<?php

namespace Plugins\BotCommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Plugins\BotCommerce\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\View;

class PublicShopController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        // Fetch published product items
        $products = Product::with('post')
            ->whereHas('post', function ($query) {
                $query->where('status', 'published');
            })
            ->get();

        // Find site settings for branding
        $site = DB::table('sites')->find(1);

        $view = 'archive-product';
        if (!View::exists($view)) {
            $view = 'botcommerce::shop.index';
        }

        return view($view, compact('products', 'site'));
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, string $slug)
    {
        $post = Post::where('type', 'product')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $product = Product::where('post_id', $post->id)->firstOrFail();
        $site = DB::table('sites')->find(1);

        $view = 'single-product';
        if (!View::exists($view)) {
            $view = 'botcommerce::shop.show';
        }

        return view($view, compact('post', 'product', 'site'));
    }
}
