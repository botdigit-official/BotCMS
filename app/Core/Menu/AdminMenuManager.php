<?php

namespace App\Core\Menu;

class AdminMenuManager
{
    /**
     * Holds registered main menu items.
     */
    protected array $menus = [];

    /**
     * Register a new main menu item.
     */
    public function register(string $key, array $options): void
    {
        $defaultOptions = [
            'label' => ucfirst($key),
            'icon' => 'document',
            'route' => null,
            'route_params' => [],
            'order' => 10,
            'submenus' => [],
        ];

        $this->menus[$key] = array_merge($defaultOptions, $options);
    }

    /**
     * Register a submenu item under a main menu.
     */
    public function registerSubmenu(string $parentKey, string $key, array $options): void
    {
        if (!isset($this->menus[$parentKey])) {
            // Auto register parent if not exists
            $this->register($parentKey, [
                'label' => ucfirst($parentKey)
            ]);
        }

        $defaultOptions = [
            'label' => ucfirst($key),
            'route' => null,
            'route_params' => [],
            'order' => 10,
        ];

        $this->menus[$parentKey]['submenus'][$key] = array_merge($defaultOptions, $options);
    }

    /**
     * Get all registered menus sorted by order.
     */
    public function all(): array
    {
        // Sort submenus of each menu first
        foreach ($this->menus as $key => &$menu) {
            if (!empty($menu['submenus'])) {
                uasort($menu['submenus'], function ($a, $b) {
                    return $a['order'] <=> $b['order'];
                });
            }
        }

        // Sort main menus
        uasort($this->menus, function ($a, $b) {
            return $a['order'] <=> $b['order'];
        });

        return $this->menus;
    }
}
