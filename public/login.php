<?php

include __DIR__ . '/../init.php';

$user = new User;
if ($user->isLoggedIn()) {
    Redirect::to('/');
    exit;
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validate->check(Input::get(), [
            'email' => [
                'required' => true,
                'email' => true
            ],
            'password' => [
                'required' => true
            ]
        ]);

        if ($validate->passed()) {
            $remember = Input::get('remember') === 'on';

            $login = $user->login(Input::get('email'), Input::get('password'), $remember);
            if ($login) {
                Session::flash('success', 'Successfully logged in!');
                Redirect::to('/');
                exit;
            }
            ec('Login failed. Try again!');
        } else {
            foreach ($validate->errors() as $error) {
                dump($error);
            }
        }
    }
}

?>

<form action="" method="post">
    <?php ec(Session::flash('success')); ?>
    <label>
        Email:
        <input type="text" name="email" value="<?= Input::get('email'); ?>">
    </label>
    <br>
    <label>
        Password:
        <input type="text" name="password">
    </label>
    <br>
    <label>
        <input type="checkbox" name="remember">
        Remember me
    </label>
    <input type="hidden" name="token" style="width: 500px;" value="<?php echo Token::generate(); ?>">
    <br>
    <input type="submit">
</form>
