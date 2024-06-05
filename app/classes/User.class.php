<?php
    class User extends Data {
        // Атрибуты
        private string $tableName = 'users';
        public ?int $id = null;
        public string $name;
        public string $surname;
        public string $patronymic;
        public string $login;
        public string $email;
        public string $password;
        public string $confirmPassword;
        public ?string $token = null;
        public string $avatar_url;
        public array $errors = [];
    
        // Валидация
        private array $validationRulesRegister = [
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

        private array $validationRulesLogin = [
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
    
        public bool $isAdmin = false;
        public bool $isGuest = true;
        public bool $isBlocked = false;
        public ?string $endBlocking = null;
    
        public Request $request;
        public Mysql $db;
    
        /**
         * Конструктор клсса
         * @param Request $request
         * @param Mysql $db
         */
        public function __construct(Request $request, Mysql $db) {
            $this->request = $request;
            $this->db = $db;

            if ($this->request->token())
                $this->identity();
        }
    
        /**
         * Загружает данные в класс с помощью родительского класса
         * @param array $data
         */
        public function load($data) {
            $this->loadData($data);

            if ($this->isAdmin()) {
                $this->isAdmin = true;
                $this->isGuest = false;
            } else {
                $this->isAdmin = false;
                $this->isGuest = true;
            }

            $blocked_info = $this->getBlockedInfo();
            if ($blocked_info['isBlocked']) {
                if ($blocked_info['end_blocking'])
                    $this->endBlocking = $blocked_info['end_blocking'];

                $this->isBlocked = true;
            } else {
                $this->endBlocking = null;
                $this->isBlocked = false;
            }
        }

        /**
         * Валидация регистрации
         * @return bool
         */
        public function validateRegister()
        {
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
    
            return empty($this->errors);
        }

        /**
         * Валидация логина
         * @return bool
         */
        public function validateLogin()
        {
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
    
            return empty($this->errors);
        }

        /**
         * Авторизация пользователя
         * @return bool
         */
        public function login()
        {
            $user = $this->db->queryAssoc("SELECT * FROM `{$this->tableName}` WHERE `login`= '{$this->login}'");

            if ($user && password_verify($this->password, $user["password"])) {
                $this->load($user);

                $this->token = bin2hex(random_bytes(32));
                $sql = "UPDATE `{$this->tableName}` SET `token`='{$this->token}' WHERE `id`='{$this->id}'";
                $this->db->query($sql);

            } else
                $this->errors['login'][] = 'Логин или пароль не совпадают с нашими записями!';

            return empty($this->errors['login']);
        }

        /**
         * Идентификация пользователя
         * @param ?int $id
         */
        public function identity($id = null)
        {
            if ($id)
                $user = $this->db->queryAssoc("SELECT * FROM `{$this->tableName}` WHERE `id`='{$id}'");
            else
                $user = $this->db->queryAssoc("SELECT * FROM `{$this->tableName}` WHERE `token`='{$this->request->token()}'");
    
            if ($user)
                $this->load($user);
        }

        /**
         * Проверка админ или нет
         * @return bool
         */
        private function isAdmin()
        {
            $user_role = $this->db->queryAssoc("SELECT roles.name AS role_name
            FROM `{$this->tableName}`
            JOIN roles ON users.role_id = roles.id WHERE users.id='{$this->id}'");

            return $user_role['role_name'] == "admin" ? true : false;
        }
        
        /**
         * Получет данные блокировки
         * @return array
         */
        private function getBlockedInfo()
        {
            $sql_block = "SELECT COUNT(*) AS isBlocked, end_blocking
            FROM `blocked_users`
            WHERE `user_id`='{$this->id}'";

            $user_block = $this->db->queryAssoc($sql_block);

            if ($user_block['end_blocking'] && strtotime($user_block['end_blocking']) <= time()) {
                $sql = "DELETE FROM `blocked_users` WHERE `user_id`='{$this->id}'";
                $this->db->query($sql);

                return $this->db->queryAssoc($sql_block);
            }

            return $user_block;
        }

        /**
         * Получение всех или определенное количество пользователей для админов
         * @return array
         */
        public function getAllUsers($limit = null)
        {
            $query = "SELECT `id` FROM `users` WHERE `id`!='{$this->id}'";

            if ($limit)
                $query .= " LIMIT {$limit}";

            $users = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);
            $result = [];

            foreach ($users as $user) {
                $userObject = new static($this->request, $this->db);
                $userObject->identity($user['id']);

                $result[] = $userObject;
            }

            return $result;
        }

        /**
         * Блокировка пользователя текущего экземпляра на всегда или на срок для админа
         * @param ?string $date
         * @return bool
         */
        public function blockUser($date = null)
        {
            if (!$this->isBlocked) {
                $sql = "INSERT INTO `blocked_users` (user_id, end_blocking) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);

                if ($date)
                    return $stmt->execute([$this->id, $date]);
                else {
                    $query = "SELECT `id`
                    FROM `posts`
                    WHERE `user_id`='{$this->id}'";

                    $posts = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

                    foreach ($posts as $post) {
                        $postObject = new Post($this);
                        $postObject->findOne($post['id']);
                        $postObject->deletePost();
                    }

                    return $stmt->execute([$this->id, null]);
                }
            } else
                return false;
        }

        /**
         * Разблокирует пользователя текущего экзепляра
         * @return bool
         */
        public function unblockUser()
        {
            if ($this->isBlocked) {
                $sql = "DELETE FROM `blocked_users` WHERE `user_id`='{$this->id}'";
                return $this->db->query($sql);
            } else
                return false;
        }

        /**
         * Выход из системы
         * @return array|bool
         */
        public function logout()
        {
            $sql = "UPDATE `{$this->tableName}` SET `token`=NULL WHERE `id`='{$this->id}'";
            return $this->db->query($sql);
        }
    
        /**
         * Сохранение данных
         * @return bool
         */
        public function save() {
            $sql = "INSERT INTO `{$this->tableName}` (name, surname, patronymic, login, email, password) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$this->name, $this->surname, $this->patronymic, $this->login, $this->email, password_hash($this->password, PASSWORD_DEFAULT)]);
        }
    
    }