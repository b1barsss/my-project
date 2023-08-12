<?php

class Session
{
    public static function put(string $name, mixed $value): mixed
    {
        $_SESSION[$name] = $value;
        return $value;
    }

    public static function exists($name): bool
    {
        return isset($_SESSION[$name]);
    }

    public static function delete($name): void
    {
        unset($_SESSION[$name]);
    }

    public static function get($name = null): mixed
    {
        if (is_null($name)) {
            return $_SESSION;
        }
        return $_SESSION[$name];
    }

    public static function flash ($name, $value = '') {
        if (self::exists($name) and self::get($name) !== '') {
            $session = self::get($name);
            self::delete($name);
            return $session;
        } else {
            self::put($name, $value);
        }
    }
}