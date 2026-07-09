<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Sites Table (for multisite support)
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->unique()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        // 2. Workspaces Table (for multi-tenant departments/workspaces within a site)
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->index();
            $table->timestamps();
            
            $table->unique(['site_id', 'slug']);
        });

        // 3. Dynamic Roles Table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->index();
            $table->timestamps();

            $table->unique(['site_id', 'slug']);
        });

        // 4. Permissions Table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->timestamps();
        });

        // 5. Permission-Role Pivot Table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        // 6. Site-User Mapping Table (Resolves Multisite User mapping)
        Schema::create('site_user', function (Blueprint $table) {
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['site_id', 'user_id']);
        });

        // 7. Settings Table (Global & Site-specific config options)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->nullable()->constrained('sites')->cascadeOnDelete();
            $table->string('key')->index();
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->unique(['site_id', 'key']);
        });

        // 8. Posts/Pages Table (Core Content Module)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type')->default('post')->index(); // post, page, block, etc.
            $table->string('title');
            $table->string('slug')->index();
            $table->longText('content')->nullable();
            $table->string('status')->default('draft')->index(); // draft, pending, published, archived
            $table->string('mime_type')->nullable(); // For media posts
            $table->timestamps();

            $table->unique(['site_id', 'type', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('site_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('workspaces');
        Schema::dropIfExists('sites');
    }
};
