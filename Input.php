<?php

class Input
{
    public static function exists(string $type = 'post'): bool
    {
        return match ($type) {
            'post' => !empty($_POST),
            'get' => !empty($_GET),
            default => false,
        };
    }

    public static function get(string $name): string
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return '';
    }
}