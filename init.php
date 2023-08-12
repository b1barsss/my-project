<?php
session_start();

include __DIR__ . '/public/functions.php';
include __DIR__ . '/classes/Database.php';
include __DIR__ . '/classes/Config.php';
include __DIR__ . '/classes/Input.php';
include __DIR__ . '/classes/Validate.php';
include __DIR__ . '/classes/Session.php';
include __DIR__ . '/classes/Token.php';
include __DIR__ . '/classes/User.php';
include __DIR__ . '/classes/Redirect.php';
include __DIR__ . '/classes/Cookie.php';

$GLOBALS['config'] = include __DIR__ . '/config.php';

if(Cookie::exists(Config::get('cookie.cookie_name')) && !Session::exists(Config::get('session.user_session'))){
    $hash = Cookie::get(Config::get('cookie.cookie_name'));
    $hashCheck = Database::getInstance()->get('user_sessions', ['hash', '=', $hash]);

    if($hashCheck->count()){
        $user = new User($hashCheck->first()->user_id);
        $user->login();
    }
}
