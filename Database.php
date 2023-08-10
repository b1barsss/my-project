<?php

class Database
{
    private static Database $instance;
    private PDO $pdo;
    private $statement;
    private bool $error = false;
    private $results;
    private $count;

    private function __construct()
    {
        try {
            $host = Config::get('mysql.host');
            $dbname = Config::get('mysql.dbname');
            $username = Config::get('mysql.username');
            $password = Config::get('mysql.password');
            $this->pdo = new PDO("mysql:host={$host};dbname={$dbname}", $username, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (!isset(self::$instance)) {
            self::$instance = new Database;
        }

        return self::$instance;
    }

    public function query(string $query, $params = []): mixed
    {
        $this->error = false;
        $this->statement = $this->pdo->prepare($query);

        if (count($params)) {
            $i = 1;
            foreach ($params as $param) {
                $this->statement->bindValue($i, $param);
                $i++;
            }
        }

        if (!$this->statement->execute()) {
            $this->error = true;
        }
        $this->results = $this->statement->fetchAll(PDO::FETCH_OBJ);
        $this->count = $this->statement->rowCount();
        return $this;
    }

    public function get(string $tableName, array $params = []): false|static
    {
        return $this->action('SELECT *', $tableName, $params);

    }

    public function delete(string $tableName, array $params = []): false|static
    {
        return $this->action('DELETE', $tableName, $params);
    }

    public function action(string $action, string $tableName, array $params = []): false|static
    {
        if (count($params) === 3) {
            $operators = ['<', '>', '>=', '<=', '=', '<>'];
            $column = $params[0];
            $operator = $params[1];
            $value = $params[2];
            if (in_array($operator, $operators)) {
                $query = "{$action} FROM {$tableName} WHERE {$column} {$operator} ?";
                $this->query($query, [$value]);
                return $this;
            }
        }

        if (empty($params)) {
            $query = "{$action} FROM {$tableName}";
            $this->query($query);
            return $this;
        }

        return false;
    }

    public function insert(string $tableName, array $data): bool
    {

        $columns = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$tableName} ({$columns}) VALUES ($values)";

        return !$this->query($query, $data)->error();
    }

    public function update(string $tableName, int|string $id, array $data)
    {

        $sets = array_map(function ($item) {
            return "$item=?";
        }, array_keys($data));
        $sets = implode(',', $sets);

        $query = "UPDATE {$tableName} SET {$sets} WHERE id=?";
        $data[] = $id;

        return !$this->query($query, $data)->error();
    }

    public function first()
    {
        if (!empty($this->results())) {
            return $this->results()[0];
        }

        return [];
    }

    public function error()
    {
        return $this->error;
    }

    public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }
}