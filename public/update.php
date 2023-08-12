<?php

include __DIR__ . '/../init.php';

$user = new User;

if (Input::exists() and Token::check(Input::get('token'))) {
    $validate = new Validate();

    $validate->check(Input::get(), [
        'username' => [
            'required' => true,
            'min' => 2,
            'max' => 15
        ]
    ]);

    if ($validate->passed()) {
        $user->update(['username' => Input::get('username')]);
        Session::flash('success', 'Profile updated successfully!');
        Redirect::to('update.php');
        exit;
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
        Username:
        <input type="text" name="username" value="<?= $user->data()->username; ?>">
    </label>
    <br>
    <input type="hidden" name="token" value="<?= Token::generate(); ?>">
    <br>
    <input type="submit">
</form>
