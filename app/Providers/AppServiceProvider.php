<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Facades\PostType;
use App\Core\Facades\AdminMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('botcms.hooks', function () {
            return new \App\Core\Hooks\HookManager();
        });

        $this->app->singleton('botcms.posttypes', function () {
            return new \App\Core\PostType\PostTypeManager();
        });

        $this->app->singleton('botcms.adminmenu', function () {
            return new \App\Core\Menu\AdminMenuManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Register Core Dashboard Menus
        AdminMenu::register('dashboard', [
            'label' => 'Dashboard',
            'icon' => 'home',
            'route' => 'admin.dashboard',
            'order' => 1
        ]);

        AdminMenu::register('pages', [
            'label' => 'Pages',
            'icon' => 'pages',
            'route' => 'admin.pages',
            'order' => 5
        ]);

        // 2. Register default Custom Post Types (CPTs) & Menus
        PostType::register('portfolio', [
            'label' => 'Portfolio',
            'singular_label' => 'Project',
            'icon' => 'briefcase',
            'supports' => ['title', 'editor', 'thumbnail'],
            'fields' => [
                'client_name' => ['type' => 'text', 'label' => 'Client Name', 'placeholder' => 'Enter Client Name'],
                'project_date' => ['type' => 'date', 'label' => 'Completion Date'],
                'project_url' => ['type' => 'url', 'label' => 'Project URL', 'placeholder' => 'https://...'],
            ]
        ]);

        AdminMenu::register('portfolio', [
            'label' => 'Portfolio',
            'icon' => 'briefcase',
            'route' => 'admin.cpt',
            'route_params' => ['type' => 'portfolio'],
            'order' => 10
        ]);

        PostType::register('testimonial', [
            'label' => 'Testimonials',
            'singular_label' => 'Testimonial',
            'icon' => 'chat',
            'supports' => ['title'],
            'fields' => [
                'client_name' => ['type' => 'text', 'label' => 'Client Name', 'placeholder' => 'Client Name'],
                'company' => ['type' => 'text', 'label' => 'Company Name', 'placeholder' => 'Company Name'],
                'quote' => ['type' => 'textarea', 'label' => 'Client Quote', 'placeholder' => 'Write quote testimonial...'],
            ]
        ]);

        AdminMenu::register('testimonial', [
            'label' => 'Testimonials',
            'icon' => 'chat',
            'route' => 'admin.cpt',
            'route_params' => ['type' => 'testimonial'],
            'order' => 15
        ]);

        // 3. Register Core Developer & Settings Menus
        AdminMenu::register('themes', [
            'label' => 'Themes',
            'icon' => 'themes',
            'route' => 'admin.themes',
            'order' => 80
        ]);

        AdminMenu::register('plugins', [
            'label' => 'Plugins',
            'icon' => 'plugins',
            'route' => 'admin.plugins',
            'order' => 85
        ]);

        AdminMenu::register('settings', [
            'label' => 'Settings',
            'icon' => 'settings',
            'route' => 'admin.settings',
            'order' => 90
        ]);
    }
}
