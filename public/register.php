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
        $validation = $validate->check(Input::get(), [
            'username' => [
                'required' => true,
                'min' => 2,
                'max' => 15,
            ],

            'email' => [
                'required' => true,
                'email' => true,
                'unique' => 'users'
            ],

            'password' => [
                'required' => true,
                'min' => 3,
                'max' => 15
            ],

            'password_confirm' => [
                'required' => true,
                'matches' => 'password'
            ]
        ]);

        if ($validation->passed()) {
            $user->create([
                'username' => Input::get('username'),
                'email' => Input::get('email'),
                'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT)
            ]);
            Session::flash('success', 'You registered successfully!');
            Redirect::to('login.php');
            exit;
        } else {
            echo "=============================================";
            echo '<br>';
            foreach ($validation->errors() as $error) {
                echo $error;
                echo '<br>';
            }
            echo "=============================================";
        }
    }

}

?>

<form action="" method="post">
    <?php ec(Session::flash('success')); ?>
    <label>
        Username:
        <input type="text" name="username" value="<?= Input::get('username'); ?>">
    </label>
    <br>
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
        Password_confirm:
        <input type="text" name="password_confirm">
    </label>
    <input type="hidden" name="token" style="width: 500px;" value="<?php echo Token::generate(); ?>">
    <br>
    <input type="submit">
</form>
