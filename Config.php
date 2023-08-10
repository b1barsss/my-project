<?php

class Config {
    public static function get(string $path = ''){
        $path = explode(".", $path);
        if ($path) {
            $config = $GLOBALS['config'];
            foreach ($path as $key) {
//                var_dump($key);
//                var_dump($config);
//                var_dump(isset($config[$key]));

                if (isset($config[$key])) {
                    $config = $config[$key];
                }
            }

            return $config;
        }
        return false;
    }
}
