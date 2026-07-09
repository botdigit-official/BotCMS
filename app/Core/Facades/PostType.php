<?php

namespace App\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(string $name, array $options)
 * @method static array all()
 * @method static array|null get(string $name)
 * @method static bool exists(string $name)
 * 
 * @see \App\Core\PostType\PostTypeManager
 */
class PostType extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'botcms.posttypes';
    }
}
