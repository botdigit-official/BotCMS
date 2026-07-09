# BotCMS

**A Laravel-powered Content Management Platform**  
*Modular • AI Native • Headless • Multi-Site • Marketplace Ready • Enterprise Ready*

BotCMS is an advanced, modular-monolith alternative to traditional content management platforms. Designed as a modern, secure, and blazing-fast engine, BotCMS allows you to build several editions (Starter, Tiny, Headless, Business, Commerce, Enterprise) from the exact same core without code bloat.

---

## Key Features

- 🧩 **Modular Monolith**: Core modules reside in `Modules/`, decoupling business logic from standard application boot.
- ⚡ **Secure Hooks Engine**: A typed Action and Filter hook system (`Hook::action()`, `Hook::filter()`) with built-in sandbox isolation and Redis caching.
- 🎨 **Dynamic Theme System**: Switch layouts and CSS styling frameworks (e.g., Tailwind CSS vs Bootstrap 5) on the fly directly via the administrator dashboard settings.
- 🔌 **Dynamic Plugin System**: Upload zip packages into the `Plugins/` directory and activate them with a single click in the admin settings without requiring Composer knowledge.
- 🌐 **Multisite Out of the Box**: Map domains to multiple virtual sites running on a unified codebase and database structure.

---

## Project Structure

```
botcms/
├── Modules/             # Core Feature Modules (Auth, Dashboard)
├── Plugins/             # ZIP-uploadable Extension Packages (SEO, Gallery, etc.)
├── Themes/              # Frontend Visual Layouts (Default, BootstrapDemo)
├── Workspaces/          # Multi-site tenant layouts and file uploads
├── app/
│   └── Core/
│       └── Hooks/       # Secure Hook Engine (Action/Filter Manager)
├── config/              # Standard Laravel Configurations
├── database/            # Migrations & SQLite database
└── routes/              # Main route file definitions
```

---

## Quick Start (Local Development)

### 1. Requirements
- PHP 8.4+ (PHP 8.5 recommended)
- Composer
- SQLite (or PostgreSQL)

### 2. Setup & Installation (1-Click Installer)

Run the unified, interactive installer script:
```bash
./install.sh
```
This script will automatically verify your environment, install PHP dependencies, configure your database driver (SQLite, MySQL, or PostgreSQL), create the database, run migrations, and seed initial values.

### 3. Running the Server

Start the development server:
```bash
php artisan serve
```

The application will be running at [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## Testing & Authentication

### Default Admin Account
- **URL**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
- **Email**: `admin@botcms.local`
- **Password**: `admin123`

### Testing the Site Switcher & SEO Plugin
1. Open the homepage at `http://127.0.0.1:8000`. You will see the **Default Theme** loaded using **Tailwind CSS**.
2. Notice the `[SEO Active]` badge in the title, and the **SEO Audit Webhook Link** in the content section. These are injected dynamically by the SEO Plugin located in `Plugins/SEO`.
3. Click **Admin Dashboard** and log in.
4. Go to **Settings** in the sidebar.
5. Change the active theme to **BootstrapDemo**, uncheck the SEO Plugin from the active list, and click **Save**.
6. Refresh the homepage. The visual styling will instantly re-render using **Bootstrap 5**, and the SEO enhancements and dynamic webhook links will disappear, illustrating clean runtime modular decoupling!

---

## Running Automated Tests

Run the full platform test suite to verify module isolation, hook bindings, and authentication flows:
```bash
php artisan test
```

---

## License
Licensed under the [MIT License](LICENSE). Open source for everyone to use and build upon.
