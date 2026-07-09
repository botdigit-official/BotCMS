<?php

namespace Plugins\SEO;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SEOServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // 
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 1. Hook into frontend page titles
        add_filter('botcms_homepage_title', function (string $title) {
            return $title . ' | Optimised by BotCMS AI SEO';
        });

        // 2. Hook into homepage welcome text
        add_filter('botcms_homepage_welcome_text', function (string $text) {
            return $text . ' <span class="text-xs bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-full align-middle font-mono font-normal">SEO Active</span>';
        });

        // 3. Hook into homepage content footer (Action)
        add_action('botcms_homepage_content_footer', function () {
            echo '
            <div class="mt-4 p-4 rounded-xl border border-dashed border-indigo-500/20 bg-indigo-500/5 max-w-lg mx-auto text-left">
                <span class="text-xs text-indigo-400 font-mono font-bold block mb-1">SEO Audit Webhook Route:</span>
                <p class="text-xs text-slate-400 mb-2">The SEO plugin registered a custom route dynamically.</p>
                <a href="/seo-audit" class="text-xs text-blue-400 hover:underline font-semibold font-mono">Run SEO Page Audit &rarr;</a>
            </div>
            ';
        });

        // 4. Register a dynamic route
        $this->registerPluginRoutes();
    }

    /**
     * Register plugin-specific routes dynamically
     */
    protected function registerPluginRoutes(): void
    {
        Route::middleware('web')->group(function () {
            Route::get('seo-audit', function () {
                return response()->json([
                    'plugin' => 'SEO Plugin',
                    'version' => '1.1.0',
                    'status' => 'healthy',
                    'audit' => [
                        'meta_tags' => 'present',
                        'robots_txt' => 'optimized',
                        'sitemap_xml' => 'generated',
                        'keyword_density' => 'optimized (AI-assisted)',
                        'security_headers' => 'strict'
                    ],
                    'timestamp' => now()->toIso8601String()
                ]);
            });
        });
    }
}
