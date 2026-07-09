# Contributing to BotCMS

Welcome to the **BotCMS** developer contribution guidelines! We are excited that you want to contribute to building a modern, modular alternative to WordPress and Shopify. 

To ensure the codebase remains clean, fast, and secure, please follow these guidelines when writing code or submitting pull requests.

---

## 1. Project Philosophy & Guidelines

*   **Modular Monolith**: We avoid scattering files across directories. Keep core features in `Modules/`, custom extensions in `Plugins/`, and visual styles in `Themes/`.
*   **Security First**:
    *   Never write raw SQL queries. Always use Laravel's Eloquent ORM or Query Builder with Parameter Bindings.
    *   All plugin hooks are wrapped in isolated try-catch blocks to prevent buggy plugin code from crashing the main boot loader.
*   **AI-Native Design**: Keep template files easily accessible in the code repository (`page-{$slug}.blade.php` and `single-{$type}.blade.php`). This allows coding assistants (like Gemini, Copilot) to build page layouts directly in code, bypassing complex database administration menus.

---

## 2. Setting Up Your Development Environment

1.  **Fork & Clone the Repository**:
    ```bash
    git clone https://github.com/botdigit-official/BotCMS.git
    cd BotCMS
    ```
2.  **Run the Installer**:
    Run the 1-click interactive installation script to configure your environment and seed database defaults:
    ```bash
    ./install.sh
    ```
3.  **Start the Local Server**:
    ```bash
    php artisan serve
    ```

---

## 3. Contribution Workflow

### Step 1: Create a Feature Branch
Create a branch with a descriptive name representing your task:
```bash
git checkout -b feature/my-amazing-plugin
```

### Step 2: Write Clean Code
*   **PHP Standards**: Follow **PSR-12** styling guidelines.
*   **Controller Logic**: Controllers should remain skinny. Delegate business logic to services, actions, or repositories.
*   **Autoloading**: Register namespaces dynamically in `composer.json` or follow standard paths. Plugins must follow `Plugins\PluginName` namespace structure.

### Step 3: Write & Run Tests
Always write tests for new features! Place feature tests in the `tests/Feature/` directory. Run the test suite before committing:
```bash
php artisan test
```
All tests must pass successfully before submitting a Pull Request.

### Step 4: Submit a Pull Request
Push your branch to GitHub and create a Pull Request against the `main` branch. Provide a clear summary of your changes, what it accomplishes, and verification results.

---

## 4. Code Standards Reference

### Registering Custom Post Types (CPTs)
Avoid bloating the database. Register CPTs with a native JSON metadata column inside any Service Provider:
```php
use App\Core\Facades\PostType;

PostType::register('portfolio', [
    'label' => 'Portfolio',
    'singular_label' => 'Project',
    'icon' => 'briefcase',
    'supports' => ['title', 'editor'],
    'fields' => [
        'client_name' => ['type' => 'text', 'label' => 'Client Name'],
    ]
]);
```

### Using Hooks (Actions & Filters)
*   **Filters**: Modify parameters or strings:
    ```php
    add_filter('botcms_homepage_welcome_text', function ($text) {
        return $text . ' | Welcome Extended!';
    });
    ```
*   **Actions**: Run operations or inject HTML inputs:
    ```php
    add_action('botcms_cpt_edit_fields_product', function ($post) {
        echo '<input type="text" name="sku" placeholder="SKU">';
    });
    ```

---

Thank you for contributing to BotCMS! Together, we can build the ultimate content management platform.
