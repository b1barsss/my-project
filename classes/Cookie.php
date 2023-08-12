<?php

class Cookie
{
    public static function put(string $name, string $value, int $time_expire = 86400): void
    {
        setcookie($name, $value, time() + $time_expire, '/');
    }

    public static function delete(string $name): void
    {
        self::put($name, '', time() - 1);
    }

    public static function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public static function get(string $name): string
    {
        return $_COOKIE[$name];
    }
}