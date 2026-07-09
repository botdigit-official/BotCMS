<?php

namespace Plugins\BotCommerce\Controllers;

use App\Http\Controllers\Controller;
use Plugins\BotCommerce\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductController extends Controller
{
    /**
     * Render SKU, Price, and Stock input fields in the product edit form.
     */
    public function renderFields($post)
    {
        $product = null;
        if ($post && $post->exists) {
            $product = Product::where('post_id', $post->id)->first();
        }

        if (!$product) {
            $product = new Product([
                'sku' => '',
                'price' => 0.00,
                'stock_quantity' => 0,
                'is_featured' => false
            ]);
        }

        return view('botcommerce::admin.fields', compact('product'));
    }

    /**
     * Save/update SKU, Price, and Stock quantity in the plugin_products table.
     */
    public function saveProduct($post, Request $request)
    {
        Product::updateOrCreate(
            ['post_id' => $post->id],
            [
                'sku' => $request->input('sku'),
                'price' => (float) $request->input('price', 0.00),
                'stock_quantity' => (int) $request->input('stock_quantity', 0),
                'is_featured' => $request->boolean('is_featured'),
            ]
        );
    }

    /**
     * Render the payment gateways settings form.
     */
    public function gateways(Request $request)
    {
        $site = DB::table('sites')->find(1);
        
        $stripeSecret = DB::table('settings')->where('key', 'stripe_secret_key')->value('value') ?: '';
        $stripePublishable = DB::table('settings')->where('key', 'stripe_publishable_key')->value('value') ?: '';
        $stripeMode = DB::table('settings')->where('key', 'stripe_mode')->value('value') ?: 'test';

        return view('botcommerce::admin.gateways', compact('stripeSecret', 'stripePublishable', 'stripeMode', 'site'));
    }

    /**
     * Save/update payment gateway credentials.
     */
    public function saveGateways(Request $request)
    {
        $request->validate([
            'stripe_secret_key' => 'nullable|string',
            'stripe_publishable_key' => 'nullable|string',
            'stripe_mode' => 'required|in:test,live',
        ]);

        DB::table('settings')->updateOrInsert(
            ['site_id' => 1, 'key' => 'stripe_secret_key'],
            ['value' => $request->input('stripe_secret_key') ?: '', 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['site_id' => 1, 'key' => 'stripe_publishable_key'],
            ['value' => $request->input('stripe_publishable_key') ?: '', 'updated_at' => now()]
        );

        DB::table('settings')->updateOrInsert(
            ['site_id' => 1, 'key' => 'stripe_mode'],
            ['value' => $request->input('stripe_mode'), 'updated_at' => now()]
        );

        return back()->with('success', 'Payment Gateway credentials saved successfully.');
    }
}
