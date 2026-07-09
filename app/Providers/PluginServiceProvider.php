<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PluginServiceProvider extends ServiceProvider
{
    /**
     * Cache array of active plugins
     */
    protected array $activePlugins = [];

    /**
     * Register services.
     */
    public function register(): void
    {
        $pluginsPath = base_path('Plugins');

        if (!File::exists($pluginsPath)) {
            return;
        }

        $this->activePlugins = $this->getActivePlugins($pluginsPath);

        foreach ($this->activePlugins as $plugin) {
            $pluginPath = $pluginsPath . '/' . $plugin['dir'];
            
            // Register plugin custom service provider if it exists
            // Expected namespace: Plugins\SEO\SEOServiceProvider
            $customProvider = "Plugins\\{$plugin['dir']}\\{$plugin['dir']}ServiceProvider";
            if (class_exists($customProvider)) {
                $this->app->register($customProvider);
            }

            // Register configuration files
            $configPath = $pluginPath . '/config';
            if (File::isDirectory($configPath)) {
                $configs = File::files($configPath);
                foreach ($configs as $configFile) {
                    $configKey = strtolower($plugin['dir']) . '.' . pathinfo($configFile->getFilename(), PATHINFO_FILENAME);
                    $this->mergeConfigFrom($configFile->getPathname(), $configKey);
                }
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $pluginsPath = base_path('Plugins');

        foreach ($this->activePlugins as $plugin) {
            $pluginPath = $pluginsPath . '/' . $plugin['dir'];
            $lowerPluginName = strtolower($plugin['dir']);

            // Load routes
            $routesPath = $pluginPath . '/routes/web.php';
            if (File::exists($routesPath)) {
                $this->loadRoutesFrom($routesPath);
            }

            $apiRoutesPath = $pluginPath . '/routes/api.php';
            if (File::exists($apiRoutesPath)) {
                $this->loadRoutesFrom($apiRoutesPath);
            }

            // Load migrations
            $migrationsPath = $pluginPath . '/database/migrations';
            if (File::isDirectory($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }

            // Load views
            $viewsPath = $pluginPath . '/resources/views';
            if (File::isDirectory($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $lowerPluginName);
            }

            // Load translations
            $langPath = $pluginPath . '/resources/lang';
            if (File::isDirectory($langPath)) {
                $this->loadTranslationsFrom($langPath, $lowerPluginName);
            }
        }
    }

    /**
     * Get active plugins. Resolves from database settings table, or falls back to plugin.json files.
     */
    protected function getActivePlugins(string $pluginsPath): array
    {
        $activePlugins = [];
        
        // 1. Try to read active plugins from DB
        $dbActiveList = null;
        try {
            if (Schema::hasTable('settings')) {
                $settings = DB::table('settings')->where('key', 'active_plugins')->first();
                if ($settings) {
                    $dbActiveList = json_decode($settings->value, true);
                }
            }
        } catch (\Throwable $e) {
            // Database might not be fully migrated or setup yet, fallback to scanning files
        }

        // 2. Scan plugin directory
        $pluginDirs = File::directories($pluginsPath);
        foreach ($pluginDirs as $dirPath) {
            $dirName = basename($dirPath);
            $jsonPath = $dirPath . '/plugin.json';
            
            if (File::exists($jsonPath)) {
                try {
                    $meta = json_decode(File::get($jsonPath), true);
                    if ($meta) {
                        $meta['dir'] = $dirName;
                        
                        // If DB specifies status, use that. Otherwise, default to plugin.json status.
                        $isEnabled = false;
                        if (is_array($dbActiveList)) {
                            $isEnabled = in_array($dirName, $dbActiveList);
                        } else {
                            $isEnabled = isset($meta['enabled']) && $meta['enabled'] === true;
                        }

                        if ($isEnabled) {
                            $activePlugins[] = $meta;
                        }
                    }
                } catch (\Throwable $e) {
                    Log::error("Failed to parse plugin.json for plugin [{$dirName}]: " . $e->getMessage());
                }
            }
        }

        return $activePlugins;
    }
}
