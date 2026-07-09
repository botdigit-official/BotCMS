<?php

namespace App\Core\Hooks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void addAction(string $hook, callable|string|array $callback, int $priority = 10, int $acceptedArgs = 1)
 * @method static void addFilter(string $hook, callable|string|array $callback, int $priority = 10, int $acceptedArgs = 1)
 * @method static void doAction(string $hook, ...$args)
 * @method static mixed applyFilters(string $hook, mixed $value, ...$args)
 * 
 * @see \App\Core\Hooks\HookManager
 */
class Hook extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'botcms.hooks';
    }
}
