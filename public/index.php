<?php
session_start();
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../Config.php';
require_once __DIR__ . '/../Input.php';
require_once __DIR__ . '/../Validate.php';
require_once __DIR__ . '/../Session.php';
require_once __DIR__ . '/../Token.php';


$GLOBALS['config'] = [
    'mysql' => [
        'host' => 'localhost',
        'dbname' => 'my-project',
        'username' => 'bibarys',
        'password' => '1',
    ],
    'session' => [
        'token_name' => 'token'
    ]
];
//$_SESSION = [];


//$Database = Database::getInstance();
//$users = $Database->get('users');
if (Input::exists()) {
    var_dump(Input::get('token'));
    echo '<br>';
    var_dump($_SESSION);
//    var_dump(Session::get('token'));
//    var_dump(Token::check(Input::get(Config::get('session.token_name'))));

    if (Token::check(Input::get('token'))) {
        var_dump('XXXXXXXXXXXXXXXXXXXXX');

        $validate = new Validate();
        $validation = $validate->check($_POST, [
            'username' => [
                'required' => true,
                'min' => 2,
                'max' => 15,
                'unique' => 'users'
            ],

            'password' => [
                'required' => true,
                'min' => 3
            ],

            'password_confirm' => [
                'required' => true,
                'matches' => 'password'
            ]
        ]);

        var_dump($_POST);
        if (!$validation->passed()) {
            echo "===============";
            echo '<br>';
            foreach ($validation->errors() as $error) {
                echo $error;
                echo '<br>';
            }
            echo "===============";
        }
    }

}


?>

<form action="/" method="post">
    <label>
        Username:
        <input type="text" name="username" value="<?= Input::get('username'); ?>">
    </label>
    <br>
    <label>
        Password:
        <input type="text" name="password">
    </label>
    <br>
    <label>
        Password_confirm:
        <input type="text" name="password_confirm">
    </label>
    <input type="text" name="token" value="<?php echo Token::generate();?>">
    <br>
    <input type="submit">
    <?php var_dump($_SESSION); ?>
    <?php echo '==================='; ?>

</form>
