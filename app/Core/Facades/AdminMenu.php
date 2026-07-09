<?php

namespace App\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(string $key, array $options)
 * @method static void registerSubmenu(string $parentKey, string $key, array $options)
 * @method static array all()
 * 
 * @see \App\Core\Menu\AdminMenuManager
 */
class AdminMenu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'botcms.adminmenu';
    }
}
