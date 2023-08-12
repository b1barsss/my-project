<?php

include __DIR__ . '/../init.php';

$user = new User;

if (Input::exists() and Token::check(Input::get('token'))) {
    $validate = new Validate();

    $validate->check(Input::get(), [
        'password' => [
            'required' => true,
        ],

        'password_new' => [
            'required' => true,
            'min' => 3,
            'max' => 15
        ],

        'password_new_confirm' => [
            'required' => true,
            'matches' => 'password_new'
        ],
    ]);

    if ($validate->passed()) {
        $password = Input::get('password');
        $password_new = Input::get('password_new');
        if (password_verify($password, $user->data()->password)) {
            $user->update(['password' => password_hash($password_new, PASSWORD_DEFAULT)]);
            Session::flash('success', 'Profile password was updated successfully!');
            Redirect::to('/');
            exit;
        } else {
            ec("Oops! The Current Password you entered doesn't match. Please check and try again.");
        }
    } else {
        foreach ($validate->errors() as $error) {
            dump($error);
        }
    }
}
?>

<form action="" method="post">
    <?= Session::flash('success'); ?>
    <label>
        Current Password:
        <input type="text" name="password">
    </label>
    <br>
    <label>
        New Password:
        <input type="text" name="password_new">
    </label>
    <br>
    <label>
        New Password confirmation:
        <input type="text" name="password_new_confirm">
    </label>
    <br>
    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
    <br>
    <input type="submit">
</form>
