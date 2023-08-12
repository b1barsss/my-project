<?php

class Config
{
    public static function get(string $path = '')
    {
        if (empty($path)) {
            return '';
        }

        $path = explode(".", $path);
        $config = $GLOBALS['config'];
        foreach ($path as $key) {
            if (isset($config[$key])) {
                $config = $config[$key];
            }
        }

        return $config;
    }
}
