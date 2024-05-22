<?php
    class User {

        // Атрибуты
        private $tableName = 'users';
        public $id;
        public $name;
        public $surname;
        public $patronymic;
        public $login;
        public $email;
        public $password;
        public $confirmPassword;
        public $token;
        public $errors = [];
    
        // Валидация
        private $validationRulesRegister = [
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

        private $validationRulesLogin = [
            'login' => [
                'required' => true,
                'min' => 2,
                'max' => 10,
            ],
            'password' => [
                'required' => true,
                'min' => 8,
                'max' => 255
            ],
        ];
    
        // Авторизация
        private $adminLogin = 'admin';
        private $adminPassword = 'password';

        private $isGuest;
        private $isAdmin;
    
        private Request $request;
        private Mysql $db;
    
        // Конструктор
        public function __construct($request, $db) {
            $this->request = $request;
            $this->db = $db;

            if($this->request->token())
                $this->identity();
        }
    
        // Методы
        public function load($data) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }

            if ($this->isAdmin()) {
                $this->isGuest = false;
                $this->isAdmin = true;
            } else {
                $this->isGuest = true;
                $this->isAdmin = false;
            }
        }
    
        public function validateRegister() {
            foreach ($this->validationRulesRegister as $field => $rules) {
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

        public function validateLogin() {
            foreach ($this->validationRulesLogin as $field => $rules) {
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
                    }
                }
            }
    
            return count($this->errors) > 0;
        }

        public function login()
        {
            $user = $this->db->queryAssoc("SELECT * FROM {$this->tableName} WHERE login = '{$this->login}'");

            if ($user && password_verify($this->password, $user["password"])) {
                $this->load($user);
                
                //if ($this->isAdmin()) {
                //    $this->isGuest = false;
                //    $this->isAdmin = true;
                //} else {
                //    $this->isGuest = true;
                //    $this->isAdmin = false;
                //}

                $this->token = bin2hex(random_bytes(32));
                $sql = "UPDATE {$this->tableName} SET token='{$this->token}' WHERE id={$this->id}";
                $this->db->query($sql);

            } else
                $this->errors['login'][] = 'Логин или пароль не совпадают с нашими записями!';

            return empty($this->errors['login']);
        }

        public function identity($id = null)
        {
            if ($id) {
                $user = $this->db->queryAssoc("SELECT * FROM {$this->tableName} WHERE id = {$id}");
            } else {
                $user = $this->db->queryAssoc("SELECT * FROM {$this->tableName} WHERE token = {$this->request->token()}");
            }
    
            if ($user)
                $this->load($user);
        }

        public function isAdmin() {
            return $this->login === $this->adminLogin && $this->password === $this->adminPassword;
        }

        public function logout()
        {
            $sql = "UPDATE {$this->tableName} SET token=NULL WHERE id={$this->id}";
            $this->db->query($sql);
        }
    
        public function save() {
            $sql = "INSERT INTO {$this->tableName} (name, surname, patronymic, login, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$this->name, $this->surname, $this->patronymic, $this->login, $this->email, password_hash($this->password, PASSWORD_DEFAULT)]);
        }
    
    }