<?php

return [
    //
    'database' => [
        'connection' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'my-project',
        'username' => 'bibarys',
        'password' => '1',
    ],

    'session' => [
        'token_name' => 'token',
        'user_session' => 'user'
    ],

    'cookie' => [
        'cookie_name' => 'hash',
        'time_expire' => 608400
    ]
];