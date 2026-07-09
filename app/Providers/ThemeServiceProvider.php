<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Active theme name
     */
    protected string $activeTheme = 'Default';

    /**
     * Active theme config
     */
    protected array $themeConfig = [];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->activeTheme = $this->getActiveThemeName();
        $this->themeConfig = $this->getThemeConfig($this->activeTheme);

        // Bind theme info to container
        $this->app->singleton('botcms.theme', function () {
            return [
                'name' => $this->activeTheme,
                'config' => $this->themeConfig
            ];
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $themePath = base_path("Themes/{$this->activeTheme}");

        if (File::exists($themePath)) {
            // Prepend theme's views path to the Laravel view finder so it overrides default app templates
            $viewsPath = $themePath . '/resources/views';
            if (File::isDirectory($viewsPath)) {
                View::prependLocation($viewsPath);
            }

            // Share current theme config globally with all blade views
            View::share('activeTheme', $this->activeTheme);
            View::share('themeConfig', $this->themeConfig);
        }
    }

    /**
     * Retrieve active theme from Database settings, falling back to config or Default
     */
    protected function getActiveThemeName(): string
    {
        try {
            if (Schema::hasTable('settings')) {
                $setting = DB::table('settings')->where('key', 'active_theme')->first();
                if ($setting && !empty($setting->value)) {
                    return $setting->value;
                }
            }
        } catch (\Throwable $e) {
            // Database not setup or settings table does not exist yet
        }

        return 'Default';
    }

    /**
     * Read and parse theme.json metadata
     */
    protected function getThemeConfig(string $themeName): array
    {
        $jsonPath = base_path("Themes/{$themeName}/theme.json");
        $defaultConfig = [
            'name' => $themeName,
            'version' => '1.0.0',
            'framework' => 'tailwind', // tailwind, bootstrap, custom
            'assets' => []
        ];

        if (File::exists($jsonPath)) {
            try {
                $meta = json_decode(File::get($jsonPath), true);
                if (is_array($meta)) {
                    return array_merge($defaultConfig, $meta);
                }
            } catch (\Throwable $e) {
                Log::error("Failed to parse theme.json for [{$themeName}]: " . $e->getMessage());
            }
        }

        return $defaultConfig;
    }
}
