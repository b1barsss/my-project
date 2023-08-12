<?php

class User
{
    private Database $database;

    private $data;
    private $session_name;
    private $cookie_name;
    private $isLoggedIn;
    private $users_table;

    public function __construct($user = null)
    {
        $this->database = Database::getInstance();
        $this->session_name = Config::get('session.user_session');
        $this->cookie_name = Config::get('cookie.cookie_name');
        $this->users_table = 'users';
        if (is_null($user)) {
            if (Session::exists($this->session_name)) {
                $user = Session::get($this->session_name); //id
            }
        }

        if ($this->find($user)) {
            $this->isLoggedIn = true;
        }
    }

    public function create($fields = []): void
    {
        $this->database->insert('users', $fields);
    }

    public function login(string|null $email = null, string|null $password = null, bool $remember = false): bool
    {

        if (is_null($email) and is_null($password) and $this->exists()) {
            Session::put($this->session_name, $this->data()->id);
            return true;
        } else {
            $user = $this->find($email);
            if ($user) {
                $user = $this->data();
                if (password_verify($password, $user->password)) {
                    Session::put($this->session_name, $user->id);

                    if ($remember) {
                        $hash = hash('sha256', uniqid());
                        $user_session = $this->database->get('user_sessions', ['user_id', '=', $user->id])->first();
                        if (!empty($user_session)) {
                            $hash = $user_session->hash;
                        } else {
                            $this->database->insert('user_sessions', [
                                'user_id' => $user->id,
                                'hash' => $hash
                            ]);
                        }

                        Cookie::put($this->cookie_name, $hash, Config::get('cookie.time_expire'));
                    }
                    return true;
                }
            }
        }
        Session::delete($this->session_name);
        return false;
    }

    public function logout(): void
    {
        $this->database->delete('user_sessions', ['user_id', '=', $this->data()->id]);
        Cookie::delete($this->cookie_name);
        Session::delete($this->session_name);
    }

    public function find(string $value = null): bool
    {
        if (is_numeric($value)) {
            $this->data = $this->database->get($this->users_table, ['id', '=', $value])->first();
        } else {
            $this->data = $this->database->get($this->users_table, ['email', '=', $value])->first();
        }

        if (!empty($this->data)) {
            return true;
        }

        return false;
    }

    public function update(array $values = [], int|null $id = null): void
    {
        if (is_null($id) and $this->isLoggedIn()) {
            $id = $this->data()->id;
        }
        $this->database->update($this->users_table, $id, $values);
    }

    public function hasPermissions(string $key) {
        $group = $this->database->get('groups', ['id', '=', $this->data()->group_id]);
        if ($group->count() > 0) {
            $permissions = json_decode($group->first()->permissions, true);

            if($permissions[$key] ??= false){
                return true;
            }
        }
        return false;
    }

    public function data()
    {
        return $this->data;
    }

    public function isLoggedIn(): bool
    {
        return $this->isLoggedIn ?? false;
    }

    public function exists(): bool
    {
        return !empty($this->data());
    }
}