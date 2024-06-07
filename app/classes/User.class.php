<?php
    class User extends Data {
        // Атрибуты
        public string $tableName = 'users';
        public ?int $id = null;
        public string $name;
        public string $surname;
        public string $patronymic;
        public string $login;
        public string $email;
        public string $password;
        public string $confirmPassword;
        public ?string $token = null;
        public ?string $avatar_url = null;
        //public array $errors = [];

        public array $validate_name_error;
        public array $validate_surname_error;
        public array $validate_patronymic_error;
        public array $validate_login_error;
        public array $validate_email_error;
        public array $validate_password_error;
        public array $validate_confirmPassword_error;
        public array $validate_avatar_url_error;
    
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
         */
        public function validateRegister()
        {
            $validationRulesRegister = [
                'name' => [
                    'required' => true,
                    'min' => 2,
                    'max' => 10
                ],
                'surname' => [
                    'required' => true,
                    'min' => 2,
                    'max' => 15
                ],
                'patronymic' => [
                    'required' => true,
                    'min' => 2,
                    'max' => 20
                ],
                'login' => [
                    'required' => true,
                    'min' => 5,
                    'max' => 15,
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
                'avatar_url' => [
                    'file_size_max' => 1024,
                    'file_types' => 'jpg|png|jpeg',
                ]
            ];
            
            Validator::make($this, $validationRulesRegister);
    
            //return empty($this->errors);
        }

        /**
         * Валидация логина
         */
        public function validateLogin()
        {
            $validationRulesLogin = [
                'login' => [
                    'required' => true,
                    'max' => 255,
                ],
                'password' => [
                    'required' => true,
                    'max' => 255,
                ],
            ];

            Validator::make($this, $validationRulesLogin);
    
            //return empty($this->errors);
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
                $this->validate_login_error[] = 'Логин или пароль не совпадают с нашими записями!';

            return empty($this->validate_login_error);
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
            if (!$this->id) return false;

            $user_role = $this->db->queryAssoc("SELECT roles.name AS role_name
            FROM `{$this->tableName}`
            JOIN `roles` ON users.role_id = roles.id WHERE users.id='{$this->id}'")['role_name'];

            return $user_role == "admin" ? true : false;
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

                    $this->deleteAllComments();

                    return $stmt->execute([$this->id, null]);
                }
            } else
                return false;
        }

        public function deleteAllComments()
        {
            $sql = "DELETE FROM `comments` WHERE `user_id`='{$this->id}'";
            return $this->db->query($sql);
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
            if (isset($_FILES['avatar_url']) && $_FILES['avatar_url']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
                $fileName = $_FILES['uploadedFile']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                $uploadFileDir = './uploaded_files/';
                $dest_path = $uploadFileDir . $newFileName;
                move_uploaded_file($fileTmpPath, $dest_path);
            }

            $sql = "INSERT INTO `{$this->tableName}` (name, surname, patronymic, login, email, password, avatar_url) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$this->name, $this->surname, $this->patronymic, $this->login, $this->email, password_hash($this->password, PASSWORD_DEFAULT), $dest_path ?? null]);
        }
    
    }