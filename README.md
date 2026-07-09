# BotCMS Platform

**A Laravel-powered Content Management Platform**  
*Modular Monolith • AI-Native • JSON-Metadata CPTs • Multi-Site • Marketplace Ready*

BotCMS is an advanced, high-performance alternative to WordPress and Shopify. Built on Laravel 13+ and PHP 8.4+, BotCMS combines the dynamic extensibility of classic CMS platforms with the security, speed, and clean MVC structure of modern web applications.

---

## 📊 Technical Comparison: WordPress vs. Shopify vs. BotCMS

| Feature | WordPress | Shopify | BotCMS (Our Monolith) |
| :--- | :--- | :--- | :--- |
| **Framework/Language** | Legacy PHP (Procedural) | Closed SaaS (Ruby/Liquid) | Modern PHP 8.4+ / Laravel 13 (MVC) |
| **Database Schema** | Bloated EAV (`wp_postmeta`) | Proprietary API Cache | **Clean SQL + JSON Metadata Columns** |
| **Response Speed** | 150ms - 500ms (Uncached) | Variable (Hosted API) | **Sub-15ms** (Native database compile) |
| **AI Compatibility** | Poor (Requires heavy DB setups) | Medium (Theme Liquid files) | **Excellent** (Code-first template files) |
| **Extensibility** | Messy Hook actions | App Bridge Webhooks (Slow) | **Secure Sandbox Action/Filter Hooks** |
| **Architecture** | Spaghetti legacy files | Multi-tenant SaaS | **Decoupled Modular Monolith** |

---

## 🧩 Foundational Architecture

BotCMS is designed around three decoupled, recursive folders residing in the workspace root:

1. **`Modules/` (Core Application Layer)**: Holds essential system packages like **Auth** (login, roles, RBAC permissions) and **Dashboard** (visual control panel screens).
2. **`Plugins/` (Logical Extension Layer)**: Self-contained packages with custom routes, controllers, models, and migrations. They can inject HTML input fields into forms using core Action Hooks and save extra data into custom SQL tables.
3. **`Themes/` (Visual Frontend Layer)**: Folder structures containing custom templates and CSS styling configurations. Switching active themes dynamically swaps the CSS framework engine (Tailwind CSS vs. Bootstrap 5).

---

## 💡 Key Developer & AI Features

### 1. Hybrid Code-First Templates (WordPress & Shopify Style)
Non-technical managers can create pages in the Admin Dashboard. However, if a developer or coding AI wants to build a highly customized, interactive page:
* Simply create a file named `page-{$slug}.blade.php` (e.g. `page-about-us.blade.php`) inside your active theme directory (`Themes/{activeTheme}/resources/views/`).
* **Resolution Priority**: When a user visits `/about-us`, BotCMS scans the theme directory first. If the file exists, it renders the template file directly, ignoring the database content!

### 2. Custom Post Types & JSON Metadata (No Bloat CPTs)
Register new post types (e.g. Portfolio, Testimonials) at boot time:
```php
use App\Core\Facades\PostType;

PostType::register('portfolio', [
    'label' => 'Portfolio',
    'singular_label' => 'Project',
    'icon' => 'briefcase',
    'supports' => ['title', 'editor'],
    'fields' => [
        'client_name' => ['type' => 'text', 'label' => 'Client Name'],
        'project_date' => ['type' => 'date', 'label' => 'Completion Date'],
    ]
]);
```
* **Auto-Generated Admin UI**: Forms, fields, and tables are generated automatically in the Admin Dashboard.
* **Database Performance**: Saved attributes are stored in a native `metadata` JSON column on the `posts` table—retaining dynamic flexibility while utilizing native database JSON index compiled speeds.

### 3. Reference MVC Plugin: BotCommerce (`Plugins/BotCommerce`)
We have built a foundational E-Commerce starter plugin showing how plugins can extend the core platform:
* **Custom Migrations**: Creates a `plugin_products` table containing `sku`, `price`, and `stock_quantity`.
* **Hierarchical Sidebar Menus**: Dynamic parent-child navigation registry using the `AdminMenu` facade, creating nested Products, Orders, and Stripe Gateways screens.
* **Action Hook Injections**: Injects inputs into the dynamic product CPT edit form using `botcms_cpt_edit_fields_product` and saves data to its custom table using `botcms_cpt_saved_product`.
* **Public Shop Catalog**: Registers public routes `/shop` and `/shop/product/{slug}` utilizing its own models and Blade views.

---

## 🚀 Quick Start (Local Development)

### 1. Requirements
* PHP 8.4+ (PHP 8.5 recommended)
* Composer 2.9+
* SQLite (default) or PostgreSQL/MySQL

### 2. Setup & Installation (1-Click Installer)
Run the interactive setup script in the workspace root:
```bash
./install.sh
```
This script validates your environment, installs dependencies, prompts you to select your preferred database driver, runs migrations, and seeds defaults.

### 3. Start Development Server
```bash
php artisan serve
```
Open [http://127.0.0.1:8000](http://127.0.0.1:8000) to view the public site.

---

## 🔐 Credentials & Testing

* **Admin URL**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
* **Default Super Admin**:
  * **Email**: `admin@botcms.local`
  * **Password**: `admin123`

### Run Automated Tests
Execute the feature test suite verifying modular isolation, hooks registry, authentication, CPTs, and BotCommerce integration:
```bash
php artisan test
```

---

## 📜 Detailed Docs
For a deep dive into Hook registries, custom plugin creation, and theme configuration, refer to our detailed developer logs:
* **Markdown format**: [DOCUMENTATION.md](DOCUMENTATION.md)
* **HTML format**: [DOCUMENTATION.html](DOCUMENTATION.html)

---

## 📄 License
Licensed under the [MIT Open Source License](LICENSE). Build something extraordinary!
