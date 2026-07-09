<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $modulesPath = base_path('Modules');

        if (!File::exists($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            
            // 1. Register custom service provider if it exists
            // Expected: Modules\Auth\AuthServiceProvider
            $customProvider = "Modules\\{$moduleName}\\{$moduleName}ServiceProvider";
            if (class_exists($customProvider)) {
                $this->app->register($customProvider);
            }

            // 2. Load configurations
            $configPath = $modulePath . '/config';
            if (File::isDirectory($configPath)) {
                $configs = File::files($configPath);
                foreach ($configs as $configFile) {
                    $configKey = strtolower($moduleName) . '.' . pathinfo($configFile->getFilename(), PATHINFO_FILENAME);
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
        $modulesPath = base_path('Modules');

        if (!File::exists($modulesPath)) {
            return;
        }

        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);
            $lowerModuleName = strtolower($moduleName);

            // 1. Load routes
            $routesPath = $modulePath . '/routes/web.php';
            if (File::exists($routesPath)) {
                $this->loadRoutesFrom($routesPath);
            }

            $apiRoutesPath = $modulePath . '/routes/api.php';
            if (File::exists($apiRoutesPath)) {
                $this->loadRoutesFrom($apiRoutesPath);
            }

            // 2. Load migrations
            $migrationsPath = $modulePath . '/database/migrations';
            if (File::isDirectory($migrationsPath)) {
                $this->loadMigrationsFrom($migrationsPath);
            }

            // 3. Load views
            $viewsPath = $modulePath . '/resources/views';
            if (File::isDirectory($viewsPath)) {
                $this->loadViewsFrom($viewsPath, $lowerModuleName);
            }

            // 4. Load translations
            $langPath = $modulePath . '/resources/lang';
            if (File::isDirectory($langPath)) {
                $this->loadTranslationsFrom($langPath, $lowerModuleName);
            }
        }
    }
}
