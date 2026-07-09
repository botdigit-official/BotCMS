<?php

namespace Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Core\Hooks\Facades\Hook;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard overview.
     */
    public function index(Request $request)
    {
        $siteId = $request->get('current_site_id');
        
        $postsCount = DB::table('posts')->where('site_id', $siteId)->where('type', 'post')->count();
        $pagesCount = DB::table('posts')->where('site_id', $siteId)->where('type', 'page')->count();
        $usersCount = DB::table('site_user')->where('site_id', $siteId)->count();

        // Retrieve installed themes
        $themes = $this->getInstalledThemes();
        $activeTheme = DB::table('settings')->where('site_id', $siteId)->where('key', 'active_theme')->value('value') ?: 'Default';

        // Retrieve installed plugins
        $plugins = $this->getInstalledPlugins($siteId);

        // Get hooks information
        $hookManager = app('botcms.hooks');
        $registeredActions = $hookManager->getActions();
        $registeredFilters = $hookManager->getFilters();

        return view('dashboard::index', compact(
            'postsCount',
            'pagesCount',
            'usersCount',
            'themes',
            'activeTheme',
            'plugins',
            'registeredActions',
            'registeredFilters'
        ));
    }

    /**
     * Show settings dashboard.
     */
    public function settings(Request $request)
    {
        $siteId = $request->get('current_site_id');
        
        $settings = DB::table('settings')->where('site_id', $siteId)->pluck('value', 'key')->toArray();
        $themes = $this->getInstalledThemes();
        $plugins = $this->getInstalledPlugins($siteId);

        return view('dashboard::settings', compact('settings', 'themes', 'plugins'));
    }

    /**
     * Save settings.
     */
    public function saveSettings(Request $request)
    {
        $siteId = $request->get('current_site_id');
        
        $request->validate([
            'site_name' => 'required|string|max:255',
        ]);

        // Save site_name
        DB::table('settings')->updateOrInsert(
            ['site_id' => $siteId, 'key' => 'site_name'],
            ['value' => $request->input('site_name'), 'updated_at' => now()]
        );

        // Trigger action hook to inform system that settings have changed
        do_action('botcms_settings_updated', $siteId, $request->except('_token'));

        return redirect()->route('admin.settings')->with('success', 'Settings updated successfully.');
    }

    /**
     * Show themes management page.
     */
    public function themes(Request $request)
    {
        $siteId = $request->get('current_site_id');
        $themes = $this->getInstalledThemes();
        $activeTheme = DB::table('settings')->where('site_id', $siteId)->where('key', 'active_theme')->value('value') ?: 'Default';

        return view('dashboard::themes', compact('themes', 'activeTheme'));
    }

    /**
     * Activate a theme.
     */
    public function activateTheme(Request $request, $themeName)
    {
        $siteId = $request->get('current_site_id');

        DB::table('settings')->updateOrInsert(
            ['site_id' => $siteId, 'key' => 'active_theme'],
            ['value' => $themeName, 'updated_at' => now()]
        );

        return redirect()->route('admin.themes')->with('success', "Theme [{$themeName}] activated successfully.");
    }

    /**
     * Upload and install a theme ZIP.
     */
    public function uploadTheme(Request $request)
    {
        $request->validate([
            'theme_zip' => 'required|file|mimes:zip|max:20480', // Max 20MB
        ]);

        $zipFile = $request->file('theme_zip');
        $zip = new \ZipArchive();

        if ($zip->open($zipFile->getPathname()) === true) {
            $themeName = pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME);
            $destination = base_path('Themes/' . $themeName);

            if (File::exists($destination)) {
                $zip->close();
                return back()->withErrors(['theme_zip' => "Theme [{$themeName}] already exists."]);
            }

            File::makeDirectory($destination, 0755, true, true);
            $zip->extractTo($destination);
            $zip->close();

            // Verify theme.json exists
            if (!File::exists($destination . '/theme.json')) {
                // Check if nested
                $subDirs = File::directories($destination);
                if (count($subDirs) === 1 && File::exists($subDirs[0] . '/theme.json')) {
                    File::copyDirectory($subDirs[0], $destination);
                    File::deleteDirectory($subDirs[0]);
                } else {
                    File::deleteDirectory($destination);
                    return back()->withErrors(['theme_zip' => 'Invalid theme format: theme.json not found in the root of the ZIP file.']);
                }
            }

            return redirect()->route('admin.themes')->with('success', "Theme [{$themeName}] installed successfully.");
        }

        return back()->withErrors(['theme_zip' => 'Failed to open ZIP file.']);
    }

    /**
     * Show plugins management page.
     */
    public function plugins(Request $request)
    {
        $siteId = $request->get('current_site_id');
        $plugins = $this->getInstalledPlugins($siteId);

        return view('dashboard::plugins', compact('plugins'));
    }

    /**
     * Toggle plugin status.
     */
    public function togglePlugin(Request $request, $pluginName)
    {
        $siteId = $request->get('current_site_id');

        $activePluginsVal = DB::table('settings')
            ->where('site_id', $siteId)
            ->where('key', 'active_plugins')
            ->value('value');
        $activePlugins = $activePluginsVal ? json_decode($activePluginsVal, true) : [];

        if (in_array($pluginName, $activePlugins)) {
            // Deactivate
            $activePlugins = array_values(array_diff($activePlugins, [$pluginName]));
            $message = "Plugin [{$pluginName}] deactivated successfully.";
        } else {
            // Activate
            $activePlugins[] = $pluginName;
            $message = "Plugin [{$pluginName}] activated successfully.";
        }

        DB::table('settings')->updateOrInsert(
            ['site_id' => $siteId, 'key' => 'active_plugins'],
            ['value' => json_encode($activePlugins), 'updated_at' => now()]
        );

        return redirect()->route('admin.plugins')->with('success', $message);
    }

    /**
     * Upload and install a plugin ZIP.
     */
    public function uploadPlugin(Request $request)
    {
        $request->validate([
            'plugin_zip' => 'required|file|mimes:zip|max:20480', // Max 20MB
        ]);

        $zipFile = $request->file('plugin_zip');
        $zip = new \ZipArchive();

        if ($zip->open($zipFile->getPathname()) === true) {
            $pluginName = pathinfo($zipFile->getClientOriginalName(), PATHINFO_FILENAME);
            $destination = base_path('Plugins/' . $pluginName);

            if (File::exists($destination)) {
                $zip->close();
                return back()->withErrors(['plugin_zip' => "Plugin [{$pluginName}] already exists."]);
            }

            File::makeDirectory($destination, 0755, true, true);
            $zip->extractTo($destination);
            $zip->close();

            // Verify plugin.json exists
            if (!File::exists($destination . '/plugin.json')) {
                // Check if nested
                $subDirs = File::directories($destination);
                if (count($subDirs) === 1 && File::exists($subDirs[0] . '/plugin.json')) {
                    File::copyDirectory($subDirs[0], $destination);
                    File::deleteDirectory($subDirs[0]);
                } else {
                    File::deleteDirectory($destination);
                    return back()->withErrors(['plugin_zip' => 'Invalid plugin format: plugin.json not found in the root of the ZIP file.']);
                }
            }

            return redirect()->route('admin.plugins')->with('success', "Plugin [{$pluginName}] installed successfully.");
        }

        return back()->withErrors(['plugin_zip' => 'Failed to open ZIP file.']);
    }

    /**
     * List all installed themes.
     */
    protected function getInstalledThemes(): array
    {
        $themesPath = base_path('Themes');
        $themes = [];

        if (File::exists($themesPath)) {
            $dirs = File::directories($themesPath);
            foreach ($dirs as $dir) {
                $dirName = basename($dir);
                $jsonPath = $dir . '/theme.json';
                $meta = [];
                
                if (File::exists($jsonPath)) {
                    $meta = json_decode(File::get($jsonPath), true) ?: [];
                }

                $themes[] = array_merge([
                    'name' => $dirName,
                    'framework' => 'tailwind',
                    'version' => '1.0.0',
                ], $meta);
            }
        }

        return $themes;
    }

    /**
     * List all installed plugins.
     */
    protected function getInstalledPlugins(int $siteId): array
    {
        $pluginsPath = base_path('Plugins');
        $plugins = [];

        // Fetch active plugins list from settings
        $activePluginsVal = DB::table('settings')
            ->where('site_id', $siteId)
            ->where('key', 'active_plugins')
            ->value('value');
        $activePlugins = $activePluginsVal ? json_decode($activePluginsVal, true) : [];

        if (File::exists($pluginsPath)) {
            $dirs = File::directories($pluginsPath);
            foreach ($dirs as $dir) {
                $dirName = basename($dir);
                $jsonPath = $dir . '/plugin.json';
                $meta = [];
                
                if (File::exists($jsonPath)) {
                    $meta = json_decode(File::get($jsonPath), true) ?: [];
                }

                $plugins[] = array_merge([
                    'name' => $dirName,
                    'description' => 'No description provided.',
                    'version' => '1.0.0',
                    'enabled' => in_array($dirName, $activePlugins),
                ], $meta);
            }
        }

        return $plugins;
    }
}
