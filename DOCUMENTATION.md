# BotCMS Platform Documentation

Welcome to the official developer and administrator documentation for **BotCMS**, a modern, modular content management platform powered by Laravel 13+ and PHP 8.4+. 

BotCMS is designed to combine the extensibility of WordPress with the performance, security, and developer ergonomics of modern Laravel modular monoliths.

---

## Table of Contents
1. [BotCMS vs. WordPress: A Technical Comparison](#1-botcms-vs-wordpress-a-technical-comparison)
2. [Hosting & Deployment Guide](#2-hosting-&-deployment-guide)
3. [The Core Hooks Engine (Actions & Filters)](#3-the-core-hooks-engine-actions-&-filters)
4. [Theme Development Guide (Tailwind / Bootstrap)](#4-theme-development-guide-tailwind-/-bootstrap)
5. [Plugin Development Guide](#5-plugin-development-guide)
6. [Security & Performance Configurations](#6-security-&-performance-configurations)
7. [Page Management & Code-First Templates (WP & Shopify Style)](#7-page-management-&-code-first-templates-wp-&-shopify-style)

---

## 1. BotCMS vs. WordPress: A Technical Comparison

| Feature | WordPress | BotCMS | Why BotCMS is Better |
| :--- | :--- | :--- | :--- |
| **Architecture** | Legacy procedural spaghetti code | Clean, modern **Modular Monolith** (Laravel) | Easier to maintain, scale, and test using PSR-4 standards. |
| **Database Structure** | Dynamic EAV pattern (`wp_postmeta`) | Structured indexed relational schema with SQLite/PostgreSQL/MySQL support | Drastically faster query execution times; no heavy joins on dynamic metadata. |
| **Hooks Engine** | Global array of closures, string callbacks | Secure, typed, isolated **HookManager** with error isolation | Uncaught exceptions in plugins do not crash the website. |
| **Asset Pipeline** | Global script/style queues (`wp_enqueue_script`) | Vite compiler with theme-level framework definitions (Tailwind/Bootstrap) | Optimized, tree-shaken asset delivery. |
| **Security** | Highly vulnerable to SQL Injection, XSS, and insecure uploads | Strict Eloquent query binding, CSRF tokens, CSP headers, and secure uploads | Native protection against major OWASP Top 10 vulnerabilities. |
| **Multi-Site** | Complex prefix-based tables | Native indexed domain routing via single database partition or multi-db | Lightweight, high-performance hosting of unlimited domains. |

---

## 2. Hosting & Deployment Guide

BotCMS runs on a standard Laravel web stack. Here is how to configure it across various hosting environments.

### Local Development / Quick Start
Run the interactive setup script:
```bash
./install.sh
```
This automatically tests database configurations and seeds credentials.

### Production: VPS (Nginx + PHP 8.4-FPM)
Below is the recommended Nginx server configuration for hosting BotCMS securely:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/botcms/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### SQLite Zero-Config Deployment
To host on cheap shared hostings using SQLite:
1. Ensure the directory `database/` is writable by the web server (`www-data`).
2. Point the document root of your hosting panel to the `/public` directory.
3. Keep the `.sqlite` file out of public reach (it resides safely inside `database/`).

---

## 3. The Core Hooks Engine (Actions & Filters)

BotCMS features a robust event-driven Hooks system that allows themes and plugins to extend execution dynamically.

### Helper Functions

#### 1. `add_action(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1)`
Registers a hook execution side-effect.
```php
add_action('botcms_homepage_content_footer', function() {
    echo "<p>Page loaded successfully.</p>";
});
```

#### 2. `do_action(string $hook, ...$args)`
Triggers all registered actions for the hook.
```php
do_action('botcms_homepage_content_footer');
```

#### 3. `add_filter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1)`
Registers a callback to modify a variable's value.
```php
add_filter('botcms_homepage_title', function(string $title) {
    return $title . ' - Custom Accent';
});
```

#### 4. `apply_filters(string $hook, mixed $value, ...$args)`
Passes the value through all registered filters and returns the final value.
```php
$title = apply_filters('botcms_homepage_title', 'Home Page');
```

---

## 4. Theme Development Guide (Tailwind / Bootstrap)

Themes are visual layouts residing in the `/Themes` directory.

### Folder Structure
```
Themes/
└── MyTheme/
    ├── theme.json
    └── resources/
        └── views/
            └── index.blade.php
```

### theme.json Configuration
Create a metadata descriptor for the theme:
```json
{
    "name": "MyTheme",
    "version": "1.0.0",
    "framework": "tailwind", 
    "description": "Premium theme using Tailwind CSS framework."
}
```
*Note: The `framework` property informs the core which CSS assets to load dynamically (e.g. `tailwind` vs `bootstrap`).*

### index.blade.php Structure
Use standard Blade directives and hooks to integrate with plugins:
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ apply_filters('botcms_homepage_title', 'Default Title') }}</title>
    <!-- Core loads Tailwind CSS automatically if theme framework is tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1>{!! apply_filters('botcms_homepage_welcome_text', 'Hello World!') !!}</h1>

    <!-- Footer Hook to let plugins inject contents dynamically -->
    @php do_action('botcms_homepage_content_footer'); @endphp
</body>
</html>
```

---

## 5. Plugin Development Guide

Plugins are modular feature packages residing in `/Plugins`. They boot automatically if checked in the administrator dashboard settings.

### Folder Structure
```
Plugins/
└── MyPlugin/
    ├── plugin.json
    └── MyPluginServiceProvider.php
```

### plugin.json Configuration
Define your plugin meta details:
```json
{
    "name": "MyPlugin",
    "version": "1.0.0",
    "description": "My first custom extension plugin.",
    "enabled": true
}
```

### MyPluginServiceProvider.php Implementation
The plugin service provider registers custom routes, views, or hooks into the system:

```php
<?php

namespace Plugins\MyPlugin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MyPluginServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // 1. Hook into homepage title filter
        add_filter('botcms_homepage_title', function(string $title) {
            return $title . ' | Dynamic Extension';
        });

        // 2. Register custom plugin routes
        Route::get('my-custom-endpoint', function() {
            return response()->json(['status' => 'OK', 'source' => 'MyPlugin']);
        });
    }
}
```

---

## 6. Security & Performance Configurations

BotCMS takes security extremely seriously, offering robust sandboxing out-of-the-box:

- **Isolated Hooks**: If a plugin hook crashes due to a syntax error or exception, the error is securely logged, and the execution proceeds instead of showing a White Screen of Death.
- **SQL Injection Prevention**: Active database queries use Laravel’s Eloquent and Query Builder Parameter Bindings exclusively. Raw SQL query statements are strictly forbidden in core modules and plugins.
- **Input Sanitization**: Always escape values rendered in Blade templates using `{{ $value }}`. Only use `{!! $value !!}` when explicitly trusting outputs processed by verified filter hooks.
- **Redis Caching**: Enable Redis caching in `.env` (`CACHE_STORE=redis`) to cache site-settings lookup maps and active plugins lists to achieve sub-15ms page response speeds.

---

## 7. Page Management & Code-First Templates (WP & Shopify Style)

BotCMS supports a hybrid structure for content creation and page template editing, giving developers/AIs the ultimate flexibility to manage pages through code:

### 1. Creating Database-Driven Pages (WordPress Style)
Non-technical administrators can manage pages via the admin dashboard:
1. Log in and navigate to **Pages** in the sidebar.
2. Click **Create New Page** and enter the Title, Slug (e.g. `services`), and Content.
3. Select **Published** and save. The page will instantly resolve at `http://127.0.0.1:8000/services` using the active theme's default `page.blade.php` fallback view.

### 2. Overriding DB Content with Code (Shopify Theme Style)
If a coding AI or developer wants to build a highly customized layout with animations, interactive JS widgets, or database queries:
1. Identify the page slug (e.g. `services`).
2. Inside your active theme directory (`Themes/{active}/resources/views/`), create a template file named:
   - `page-services.blade.php` OR
   - `pages/services.blade.php`
3. Put your custom HTML, Tailwind/Bootstrap classes, and Blade logic inside this file.
4. **Resolution Priority**: When a user accesses `/services`, BotCMS checks if the theme template file exists. If it does, **it renders the theme template file directly (ignoring the database content)**! This allows developers to code templates in the repo while allowing non-technical managers to use database fallback content.
5. If the database page has a published entry but no custom file exists, the system automatically uses the generic `page.blade.php` fallback layout in the active theme.
