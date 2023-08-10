<?php

require_once __DIR__ . '/Database.php';

class Validate
{
    private bool $passed = false;
    private array $errors = [];
    private Database $database;

    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function check(array $checkArr, array $item = []): static
    {
        foreach ($item as $name => $rules) {
            if (!isset($checkArr[$name])) {
                continue;
            }
            $checkWord = $checkArr[$name];
            foreach ($rules as $rule => $value) {
                if (empty($checkWord)) {
                    if ($rule === 'required') {
                        self::addError("Field {$name} is required!");
                    }
                    continue;
                }
                switch ($rule) {
                    case 'min':
                        if (strlen($checkWord) < $value) {
                            self::addError("Field {$name} must be at least {$value} characters");
                        }
                        break;
                    case 'max':
                        if (strlen($checkWord) > $value) {
                            self::addError("Field {$name} must be maximum {$value} characters");
                        }
                        break;
                    case 'unique':
                        if ($this->database->get($value, [$name, "=", $checkArr[$name]])->count() > 0) {
                            self::addError("{$name}: {$checkArr[$name]} is already exists!");
                        }
                        break;
                    case 'matches':
                        if ($checkWord !== $checkArr[$value]) {
                            self::addError("Field {$name} doesn't match Field {$value}!");
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        if (empty($this->errors())) {
            $this->passed = true;
        }

        return $this;
    }

    public function passed(): bool
    {
        return $this->passed;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function addError(string $error): void
    {
        $this->errors[] = $error;
    }
}