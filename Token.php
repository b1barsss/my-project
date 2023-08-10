<?php

class Token
{
    public static function generate(){
        return Session::put(Config::get('session.token_name'), md5(uniqid()));
    }

    public static function check(string $token): bool
    {
        $tokenName = Config::get('session.token_name');

        if (Session::exists($tokenName) and $token == Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }

        return false;
    }
}