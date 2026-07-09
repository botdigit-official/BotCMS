<?php

namespace Plugins\BotCommerce;

use Illuminate\Support\ServiceProvider;
use App\Core\Facades\PostType;
use App\Core\Facades\AdminMenu;
use App\Core\Hooks\Facades\Hook;
use Plugins\BotCommerce\Controllers\AdminProductController;

class BotCommerceServiceProvider extends ServiceProvider
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
            'fields' => [] // Managed via custom database table plugin_products
        ]);

        // 2. Register Custom Post Type for Orders
        PostType::register('order', [
            'label' => 'Orders',
            'singular_label' => 'Order',
            'icon' => 'chat',
            'supports' => ['title'],
            'fields' => [
                'customer_name' => ['type' => 'text', 'label' => 'Customer Name', 'placeholder' => 'Customer Full Name'],
                'total_price' => ['type' => 'text', 'label' => 'Total Price (USD)', 'placeholder' => '0.00'],
                'payment_status' => ['type' => 'text', 'label' => 'Payment Status', 'placeholder' => 'e.g. pending, paid'],
                'stripe_charge_id' => ['type' => 'text', 'label' => 'Stripe Charge ID', 'placeholder' => 'ch_...'],
            ]
        ]);

        // 3. Register dynamic Admin Menu and Submenus tree structure for BotCommerce
        AdminMenu::register('botcommerce', [
            'label' => 'BotCommerce',
            'icon' => 'shopping-cart',
            'order' => 30
        ]);

        AdminMenu::registerSubmenu('botcommerce', 'products', [
            'label' => 'Products',
            'route' => 'admin.cpt',
            'route_params' => ['type' => 'product'],
            'order' => 1
        ]);

        AdminMenu::registerSubmenu('botcommerce', 'orders', [
            'label' => 'Orders',
            'route' => 'admin.cpt',
            'route_params' => ['type' => 'order'],
            'order' => 2
        ]);

        AdminMenu::registerSubmenu('botcommerce', 'gateways', [
            'label' => 'Gateways',
            'route' => 'admin.botcommerce.gateways',
            'order' => 3
        ]);

        // 4. Hook into the Product Edit form to render SKU, Price, and Stock fields
        Hook::addAction('botcms_cpt_edit_fields_product', function ($post) {
            $controller = app(AdminProductController::class);
            echo $controller->renderFields($post);
        });

        // 5. Hook into CPT save event to store SKU, Price, and Stock into plugin_products table
        Hook::addAction('botcms_cpt_saved_product', function ($post, $request) {
            $controller = app(AdminProductController::class);
            $controller->saveProduct($post, $request);
        }, 10, 2);

        // 6. Hook into page object resolver to virtualize Cart and Checkout pages
        Hook::addFilter('botcms_resolve_page_object', function ($page, $slug, $site) {
            if ($slug === 'cart' || $slug === 'checkout') {
                return new \App\Models\Post([
                    'site_id' => $site->id,
                    'title' => ucfirst($slug),
                    'slug' => $slug,
                    'type' => 'page',
                    'status' => 'published',
                    'content' => "<!--botcommerce_{$slug}-->"
                ]);
            }
            return $page;
        }, 10, 3);

        // 7. Hook into page template resolution to load dynamic plugin views
        Hook::addFilter('botcms_resolve_page_view', function ($view, $slug, $page, $site) {
            if ($slug === 'cart' || $slug === 'checkout') {
                return "botcommerce::shop.{$slug}";
            }
            return $view;
        }, 10, 4);
    }

    /**
     * Register plugin services.
     */
    public function register(): void
    {
        //
    }
}
