<?php

class Redirect
{
    public static function to($location = null): void
    {
        if(is_null($location)) {
            return;
        }

        switch ($location) {
            case 404:
                header('HTTP/1.0 404 Not Found.');
                include __DIR__ . '/../includes/error/404.php';
                exit;
            default:
                header("Location: {$location}");
                exit;
        }
    }

}