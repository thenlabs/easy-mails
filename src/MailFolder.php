<?php
declare(strict_types=1);

namespace ThenLabs\EasyMails;

use SplObjectStorage;

/**
 * @author Andy Daniel Navarro Taño <andaniel05@gmail.com>
 * @abstract
 */
abstract class MailFolder
{
    static protected $folders = [];

    public static function get(string $name): SplObjectStorage
    {
        if (! isset(static::$folders[$name])) {
            static::$folders[$name] = new SplObjectStorage;
        }

        return static::$folders[$name];
    }

    public static function inbox(): SplObjectStorage
    {
        return static::get('inbox');
    }

    public static function sent(): SplObjectStorage
    {
        return static::get('sent');
    }

    public static function trash(): SplObjectStorage
    {
        return static::get('trash');
    }
}
