<?php

namespace Plugins\ProductCatalog;

use Illuminate\Support\ServiceProvider;
use App\Core\Facades\PostType;
use App\Core\Hooks\Facades\Hook;
use Plugins\ProductCatalog\Controllers\AdminProductController;

class ProductCatalogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap plugin services.
     */
    public function boot(): void
    {
        // 1. Register Custom Post Type for E-Commerce Products
        PostType::register('product', [
            'label' => 'Products',
            'singular_label' => 'Product',
            'icon' => 'briefcase',
            'supports' => ['title', 'editor'],
            'fields' => [] // SKU, Price, and Stock are managed via custom database table plugin_products
        ]);

        // 2. Hook into the Product Edit form to render SKU, Price, and Stock fields
        Hook::addAction('botcms_cpt_edit_fields_product', function ($post) {
            $controller = app(AdminProductController::class);
            // Render and output directly into the CPT edit form
            echo $controller->renderFields($post);
        });

        // 3. Hook into CPT save event to store SKU, Price, and Stock into plugin_products table
        Hook::addAction('botcms_cpt_saved_product', function ($post, $request) {
            $controller = app(AdminProductController::class);
            $controller->saveProduct($post, $request);
        }, 10, 2);
    }

    /**
     * Register plugin services.
     */
    public function register(): void
    {
        //
    }
}
