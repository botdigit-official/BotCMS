<?php

namespace Plugins\ProductCatalog\Controllers;

use App\Http\Controllers\Controller;
use Plugins\ProductCatalog\Models\Product;
use Illuminate\Http\Request;

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

        return view('productcatalog::admin.fields', compact('product'));
    }

    /**
     * Save/update SKU, Price, and Stock quantity in the plugin_products table.
     */
    public function saveProduct($post, Request $request)
    {
        // Simple validation rule additions can be processed if needed.
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
}
