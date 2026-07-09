<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Facades\PostType;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register default Custom Post Types (CPTs)
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
    }
}
