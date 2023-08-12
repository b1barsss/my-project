<?php

include __DIR__ . '/../init.php';

$user = new User;

ec(Session::flash('success'));

if($user->isLoggedIn()) {
    ec("Hi, {$user->data()->username}!");
    ec("<a href='logout.php'>Logout</a>");
    ec("<a href='update.php'>Update profile</a>");
    ec("<a href='update_password.php'>Update password</a>");

    if ($user->hasPermissions('admin')) {
        ec('You are admin!');
    }
} else {
    ec("<a href='login.php'>Login</a> or <a href='register.php'>Register</a>");
}
