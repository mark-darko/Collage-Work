<?php
    class User {

        // Атрибуты
        private $tableName = 'users';
        public $name;
        public $surname;
        public $patronymic;
        public $login;
        public $email;
        public $password;
        public $confirmPassword;
        public $errors = [];
    
        // Валидация
        private $validationRules = [
            'name' => [
                'required' => true,
                'min' => 2,
                'max' => 30
            ],
            'surname' => [
                'required' => true,
                'min' => 2,
                'max' => 30
            ],
            'patronymic' => [
                'required' => true,
                'min' => 2,
                'max' => 30
            ],
            'login' => [
                'required' => true,
                'min' => 2,
                'max' => 10,
                'unique' => 'login'
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'unique' => 'email'
            ],
            'password' => [
                'required' => true,
                'min' => 8,
                'max' => 255
            ],
            'confirmPassword' => [
                'required' => true,
                'matches' => 'password'
            ],
        ];
    
        // Авторизация
        private $isGuest = true;
        private $isAdmin = false;
    
        private $request;
        private Mysql $db;
    
        // Конструктор
        public function __construct($request, $db) {
            $this->request = $request;
            $this->db = $db;
        }
    
        // Методы
    
        public function load($data) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    
        public function validateRegister() {
            foreach ($this->validationRules as $field => $rules) {
                foreach ($rules as $rule => $value) {
                    switch ($rule) {
                        case 'required':
                            if (empty($this->$field)) {
                                $this->errors[$field][] = "Поле {$field} обязательно для заполнения";
                            }
                            break;
                        case 'min':
                            if (strlen($this->$field) < $value) {
                                $this->errors[$field][] = "Поле {$field} должно содержать не менее {$value} символов";
                            }
                            break;
                        case 'max':
                            if (strlen($this->$field) > $value) {
                                $this->errors[$field][] = "Поле {$field} должно содержать не более {$value} символов";
                            }
                            break;
                        case 'email':
                            if (!filter_var($this->$field, FILTER_VALIDATE_EMAIL)) {
                                $this->errors[$field][] = "Поле {$field} должно содержать действительный адрес электронной почты";
                            }
                            break;
                        case 'unique':
                            if (!$this->db->isUnique($this->tableName, $value, $this->$field)) {
                                $this->errors[$field][] = "Пользователь с такими данными уже существует";
                            }
                            break;
                        case 'matches':
                            if ($this->$field != $this->$value) {
                                $this->errors[$field][] = "Пароли не совпадают";
                            }
                            break;
                    }
                }
            }
    
            return count($this->errors) > 0;
        }
    
        public function save() {
            $sql = "INSERT INTO {$this->tableName} (name, surname, patronymic, login, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$this->name, $this->surname, $this->patronymic, $this->login, $this->email, password_hash($this->password, PASSWORD_DEFAULT)]);
        }
    
    }