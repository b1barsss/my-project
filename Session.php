<?php

class Session
{
    public static function put(string $name, mixed $value): mixed
    {

        return $_SESSION[$name] = $value;
    }

    public static function exists($name): bool
    {
        return isset($_SESSION[$name]);
    }

    public static function delete($name): void
    {
        unset($_SESSION[$name]);
    }

    public static function get($name): mixed
    {
        return $_SESSION[$name] ?? null;
    }
}