<?php

namespace App\Core\PostType;

class PostTypeManager
{
    /**
     * Array of registered post types.
     */
    protected array $types = [];

    /**
     * Register a new custom post type.
     */
    public function register(string $name, array $options): void
    {
        $defaultOptions = [
            'label' => ucfirst($name),
            'singular_label' => ucfirst($name),
            'icon' => 'document',
            'supports' => ['title', 'editor'], // title, editor, thumbnail, etc.
            'fields' => [] // Custom metadata schema: ['client_name' => ['type' => 'text', 'label' => 'Client Name']]
        ];

        $this->types[$name] = array_merge($defaultOptions, $options);
    }

    /**
     * Get all registered post types.
     */
    public function all(): array
    {
        return $this->types;
    }

    /**
     * Get options for a specific post type.
     */
    public function get(string $name): ?array
    {
        return $this->types[$name] ?? null;
    }

    /**
     * Check if post type is registered.
     */
    public function exists(string $name): bool
    {
        return isset($this->types[$name]);
    }
}
