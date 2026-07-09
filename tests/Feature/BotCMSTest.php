<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Core\Hooks\Facades\Hook;

class BotCMSTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run seeders automatically to have default site, roles, settings, etc.
        $this->seed();
    }

    /**
     * Test frontend page loading.
     */
    public function test_homepage_loads_properly_with_hooks_and_seo_plugin()
    {
        $response = $this->get('/');
        
        $response->assertStatus(200);
        // Verify SEO filter altered the title
        $response->assertSee('Optimised by BotCMS AI SEO');
        // Verify SEO welcome text filter worked
        $response->assertSee('SEO Active');
    }

    /**
     * Test dynamic plugin routes.
     */
    public function test_plugin_dynamic_route_resolves()
    {
        $response = $this->get('/seo-audit');

        $response->assertStatus(200);
        $response->assertJson([
            'plugin' => 'SEO Plugin',
            'version' => '1.1.0',
            'status' => 'healthy'
        ]);
    }

    /**
     * Test Auth routes login display.
     */
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('BotCMS');
        $response->assertSee('admin@botcms.local');
        $response->assertSee('admin123');
    }

    /**
     * Test Auth verification.
     */
    public function test_users_can_authenticate_using_the_login_form()
    {
        $response = $this->post('/login', [
            'email' => 'admin@botcms.local',
            'password' => 'admin123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/admin');
    }

    /**
     * Test Dashboard restriction.
     */
    public function test_unauthenticated_users_cannot_access_dashboard()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /**
     * Test hooks system execution.
     */
    public function test_hook_manager_filters_and_actions()
    {
        Hook::addFilter('test_filter', function ($val) {
            return $val . '-modified';
        });

        $val = Hook::applyFilters('test_filter', 'base');
        $this->assertEquals('base-modified', $val);

        $actionTriggered = false;
        Hook::addAction('test_action', function () use (&$actionTriggered) {
            $actionTriggered = true;
        });

        Hook::doAction('test_action');
        $this->assertTrue($actionTriggered);
    }

    /**
     * Test admin themes page and activation.
     */
    public function test_admin_can_manage_and_activate_themes()
    {
        $user = User::first(); // Super Admin

        $response = $this->actingAs($user)
            ->get('/admin/themes');

        $response->assertStatus(200);
        $response->assertSee('Default');
        $response->assertSee('BootstrapDemo');

        // Test activation
        $response = $this->actingAs($user)
            ->post('/admin/themes/activate/BootstrapDemo');

        $response->assertRedirect('/admin/themes');
        $this->assertEquals('BootstrapDemo', \Illuminate\Support\Facades\DB::table('settings')->where('key', 'active_theme')->value('value'));
    }

    /**
     * Test admin plugins page and toggle.
     */
    public function test_admin_can_manage_and_toggle_plugins()
    {
        $user = User::first(); // Super Admin

        $response = $this->actingAs($user)
            ->get('/admin/plugins');

        $response->assertStatus(200);
        $response->assertSee('SEO');

        // Deactivate SEO (which is active by default in our seeder)
        $response = $this->actingAs($user)
            ->followingRedirects()
            ->post('/admin/plugins/toggle/SEO');

        $response->assertStatus(200);
        $response->assertSee('Activate'); // SEO should show Activate button now
        
        $activePlugins = json_decode(\Illuminate\Support\Facades\DB::table('settings')->where('key', 'active_plugins')->value('value'), true);
        $this->assertNotContains('SEO', $activePlugins);
    }

    /**
     * Test admin page CRUD and public rendering.
     */
    public function test_admin_can_manage_pages_and_render_them()
    {
        $user = User::first(); // Super Admin

        // 1. Access index
        $response = $this->actingAs($user)->get('/admin/pages');
        $response->assertStatus(200);

        // 2. Access create form
        $response = $this->actingAs($user)->get('/admin/pages/create');
        $response->assertStatus(200);

        // 3. Store a page in DB
        $response = $this->actingAs($user)->post('/admin/pages/store', [
            'title' => 'About Us',
            'slug' => 'about-us',
            'content' => 'This page content comes from database.',
            'status' => 'published'
        ]);

        $response->assertRedirect('/admin/pages');
        
        // 4. Verify public page renders database content
        $response = $this->get('/about-us');
        $response->assertStatus(200);
        $response->assertSee('This page content comes from database.');
    }

    /**
     * Test theme-first layout priority (Shopify & WP code-first override).
     */
    public function test_theme_template_overrides_database_content()
    {
        $user = User::first(); // Super Admin

        // Create page in DB
        $this->actingAs($user)->post('/admin/pages/store', [
            'title' => 'Contact Us',
            'slug' => 'contact-us',
            'content' => 'DB Content',
            'status' => 'published'
        ]);

        // Verify default renders database content
        $response = $this->get('/contact-us');
        $response->assertSee('DB Content');

        // Create a temporary file in Themes/Default/resources/views/page-contact-us.blade.php
        $viewPath = base_path('Themes/Default/resources/views/page-contact-us.blade.php');
        
        try {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($viewPath));
            \Illuminate\Support\Facades\File::put($viewPath, '<h1>Overridden Theme Code Layout</h1>');

            // Verify page route now renders the theme file content (taking priority over DB content!)
            $response = $this->get('/contact-us');
            $response->assertStatus(200);
            $response->assertSee('Overridden Theme Code Layout');
            $response->assertDontSee('DB Content');
        } finally {
            // Cleanup
            \Illuminate\Support\Facades\File::delete($viewPath);
        }
    }

    /**
     * Test Custom Post Types CRUD and rendering with dynamic metadata JSON.
     */
    public function test_admin_can_manage_cpts_and_render_them()
    {
        $user = User::first(); // Super Admin

        // 1. Verify index page works
        $response = $this->actingAs($user)->get('/admin/cpt/portfolio');
        $response->assertStatus(200);
        $response->assertSee('Portfolio');

        // 2. Verify create page works
        $response = $this->actingAs($user)->get('/admin/cpt/portfolio/create');
        $response->assertStatus(200);
        $response->assertSee('Client Name'); // Dynamic custom field

        // 3. Store a portfolio project in DB with metadata
        $response = $this->actingAs($user)->post('/admin/cpt/portfolio/store', [
            'title' => 'My New Website Project',
            'status' => 'published',
            'content' => 'Portfolio description',
            'meta' => [
                'client_name' => 'Google Inc',
                'project_date' => '2026-07-09',
                'project_url' => 'https://google.com'
            ]
        ]);

        $response->assertRedirect('/admin/cpt/portfolio');

        // 4. Verify public page renders correctly (using fallback generic layout)
        $response = $this->get('/portfolio/my-new-website-project');
        $response->assertStatus(200);
        $response->assertSee('My New Website Project');
        $response->assertSee('Portfolio description');

        // 5. Test template overrides custom file priority
        $viewPath = base_path('Themes/Default/resources/views/single-portfolio.blade.php');
        
        try {
            \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($viewPath));
            \Illuminate\Support\Facades\File::put($viewPath, '<h1>Project Client: {{ $post->meta[\'client_name\'] }}</h1>');

            // Verify page route now renders the theme template file and retrieves JSON meta correctly
            $response = $this->get('/portfolio/my-new-website-project');
            $response->assertStatus(200);
            $response->assertSee('Project Client: Google Inc');
            $response->assertDontSee('Portfolio description'); // Because we overrode the whole layout
        } finally {
            // Cleanup
            \Illuminate\Support\Facades\File::delete($viewPath);
        }
    }

    /**
     * Test dynamic BotCommerce plugin registration, database migration, form hooks, submenus, gateways, and public shop.
     */
    public function test_plugin_bot_commerce_integration()
    {
        $user = User::first(); // Super Admin

        // 1. Verify product and order CPTs are registered by the plugin
        $this->assertTrue(app('botcms.posttypes')->exists('product'));
        $this->assertTrue(app('botcms.posttypes')->exists('order'));

        // 2. Verify BotCommerce menus and submenus are registered dynamically
        $menus = app('botcms.adminmenu')->all();
        $this->assertArrayHasKey('botcommerce', $menus);
        $this->assertArrayHasKey('products', $menus['botcommerce']['submenus']);
        $this->assertArrayHasKey('orders', $menus['botcommerce']['submenus']);
        $this->assertArrayHasKey('gateways', $menus['botcommerce']['submenus']);

        // 3. Verify admin product fields are injected via the action hook
        $response = $this->actingAs($user)->get('/admin/cpt/product/create');
        $response->assertStatus(200);
        $response->assertSee('E-Commerce Product Specifications');
        $response->assertSee('sku');

        // 4. Save a product via the CPT store endpoint (calling the plugin's save hook)
        $response = $this->actingAs($user)->post('/admin/cpt/product/store', [
            'title' => 'iPhone 17 Pro Max',
            'status' => 'published',
            'content' => 'The ultimate iPhone model.',
            'sku' => 'IPHONE-17-PRO',
            'price' => '1199.99',
            'stock_quantity' => '50',
            'is_featured' => '1'
        ]);

        $response->assertRedirect('/admin/cpt/product');

        // 5. Verify data is saved correctly in both posts and plugin_products tables
        $this->assertDatabaseHas('posts', [
            'type' => 'product',
            'title' => 'iPhone 17 Pro Max'
        ]);

        $this->assertDatabaseHas('plugin_products', [
            'sku' => 'IPHONE-17-PRO',
            'price' => 1199.99,
            'stock_quantity' => 50,
            'is_featured' => true
        ]);

        // 6. Verify Payment Gateways page rendering
        $response = $this->actingAs($user)->get('/admin/botcommerce/gateways');
        $response->assertStatus(200);
        $response->assertSee('Stripe Publishable Key');

        // 7. Verify saving payment gateway credentials
        $response = $this->actingAs($user)->post('/admin/botcommerce/gateways', [
            'stripe_publishable_key' => 'pk_test_12345',
            'stripe_secret_key' => 'sk_test_67890',
            'stripe_mode' => 'test'
        ]);
        $response->assertRedirect();
        
        $this->assertDatabaseHas('settings', [
            'key' => 'stripe_publishable_key',
            'value' => 'pk_test_12345'
        ]);

        // 8. Verify public shop index displays the product
        $response = $this->get('/shop');
        $response->assertStatus(200);
        $response->assertSee('iPhone 17 Pro Max');
        $response->assertSee('$1,199.99');

        // 9. Verify public single product page displays details
        $response = $this->get('/shop/product/iphone-17-pro-max');
        $response->assertStatus(200);
        $response->assertSee('iPhone 17 Pro Max');
        $response->assertSee('IPHONE-17-PRO');
        $response->assertSee('50 units');
    }
}
