<?php

use App\Core\Hooks\Facades\Hook;

if (!function_exists('add_action')) {
    function add_action(string $hook, callable|string|array $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        Hook::addAction($hook, $callback, $priority, $acceptedArgs);
    }
}

if (!function_exists('add_filter')) {
    function add_filter(string $hook, callable|string|array $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        Hook::addFilter($hook, $callback, $priority, $acceptedArgs);
    }
}

if (!function_exists('do_action')) {
    function do_action(string $hook, ...$args): void
    {
        Hook::doAction($hook, ...$args);
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters(string $hook, mixed $value, ...$args): mixed
    {
        return Hook::applyFilters($hook, $value, ...$args);
    }
}
