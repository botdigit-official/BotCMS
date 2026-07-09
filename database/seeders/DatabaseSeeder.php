<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Site
        $siteId = DB::table('sites')->insertGetId([
            'name' => 'BotCMS Primary Site',
            'domain' => 'localhost',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Default Workspace
        DB::table('workspaces')->insert([
            'site_id' => $siteId,
            'name' => 'Main Workspace',
            'slug' => 'main',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Create Default Permissions
        $permissions = [
            'view_dashboard' => 'View Admin Dashboard',
            'manage_settings' => 'Manage Settings',
            'manage_users' => 'Manage Users',
            'manage_posts' => 'Manage Posts',
            'publish_posts' => 'Publish Posts',
            'manage_plugins' => 'Manage Plugins',
            'manage_themes' => 'Manage Themes',
        ];

        $permissionIds = [];
        foreach ($permissions as $slug => $name) {
            $permissionIds[$slug] = DB::table('permissions')->insertGetId([
                'name' => $name,
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Create Default Roles
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'editor' => 'Editor',
            'author' => 'Author',
            'customer' => 'Customer',
        ];

        $roleIds = [];
        foreach ($roles as $slug => $name) {
            $roleIds[$slug] = DB::table('roles')->insertGetId([
                'site_id' => $siteId,
                'name' => $name,
                'slug' => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 5. Map permissions to roles
        // Super Admin gets all permissions
        foreach ($permissionIds as $permId) {
            DB::table('permission_role')->insert([
                'role_id' => $roleIds['super_admin'],
                'permission_id' => $permId,
            ]);
        }

        // Admin gets everything except plugin/theme management
        $adminPerms = ['view_dashboard', 'manage_settings', 'manage_users', 'manage_posts', 'publish_posts'];
        foreach ($adminPerms as $slug) {
            DB::table('permission_role')->insert([
                'role_id' => $roleIds['admin'],
                'permission_id' => $permissionIds[$slug],
            ]);
        }

        // Editor gets view_dashboard and posts
        $editorPerms = ['view_dashboard', 'manage_posts', 'publish_posts'];
        foreach ($editorPerms as $slug) {
            DB::table('permission_role')->insert([
                'role_id' => $roleIds['editor'],
                'permission_id' => $permissionIds[$slug],
            ]);
        }

        // 6. Create Default Super Admin User
        $user = User::create([
            'name' => 'BotCMS Administrator',
            'email' => 'admin@botcms.local',
            'password' => Hash::make('admin123'),
        ]);

        // 7. Associate User with Site and Super Admin Role
        DB::table('site_user')->insert([
            'site_id' => $siteId,
            'user_id' => $user->id,
            'role_id' => $roleIds['super_admin'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Default settings
        DB::table('settings')->insert([
            [
                'site_id' => $siteId,
                'key' => 'site_name',
                'value' => 'BotCMS Platform',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => $siteId,
                'key' => 'active_theme',
                'value' => 'Default',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => $siteId,
                'key' => 'active_plugins',
                'value' => json_encode(['SEO', 'BotCommerce']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
