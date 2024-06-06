<?php
    class Post extends Data {
        private string $tableName = 'posts';
        private User $user;
        public ?int $id = null;
        public string $title;
        public array $validate_title_error;
        public ?string $content = null;
        public array $validate_content_error;
        public string $created_at;
        public int $comment_count;
        public User $author;

        /**
         * Конструктор класса
         * @param User $user
         */
        public function __construct($user)
        {
            $this->user = $user;
        }

        public function validatePost()
        {
            $validationRulesPost = [
                'title' => [
                    'required' => true,
                    'max' => 255,
                ],
                'content' => [
                    'required' => true,
                ],
            ];

            Validator::make($this, $validationRulesPost);
        }

        /**
         * Загружает данные в класс с помощью родительского класса
         * @param array $data
         */
        public function load($data)
        {
            $this->loadData($data);
        }

        /**
         * Поиск одного поста по id
         * @param int $id
         * @return bool
         */
        public function findOne($id)
        {
            $post = $this->user->db->queryAssoc("SELECT * FROM `{$this->tableName}` WHERE `id`='{$id}'");
            
            if (!$post) return false;

            $user = new User($this->user->request, $this->user->db);
            $user->identity($post["user_id"]);

            $this->load(array_merge($post, $this->user->db->queryAssoc("SELECT COUNT(*) AS comment_count FROM `comments` WHERE `post_id`='{$id}'"), ["author" => $user]));   

            return true;
        }

        /**
         * Получить комменатрия текущего поста
         * @return array
         */
        public function getComments()
        {
            $commentObject = new Comment($this->user);
            return $commentObject->getComments($this->id);
        }

        /**
         * Отображение даты методом родительского класса
         * @param string $date
         * @return string|bool
         */
        public function displayDate($date)
        {
            return $this->formatDate($date);
        }

        /**
         * Получение всех постов или с лимитом
         * @param ?int $limit
         * @return array
         */
        public function findAll($limit = null)
        {
            $query = "SELECT posts.*, COUNT(comments.id) AS comment_count
                    FROM `{$this->tableName}`
                    LEFT JOIN `comments` ON posts.id = comments.post_id
                    GROUP BY posts.id
                    ORDER BY posts.created_at DESC";

            if ($limit)
                $query .= " LIMIT {$limit}";

            $posts = $this->user->db->query($query)->fetch_all(MYSQLI_ASSOC);

            $result = [];
            foreach ($posts as $post) {
                $postObject = new static($this->user);
                $postObject->load($post);

                // Формирование экземпляра пользователя (автора поста)
                $userObject = new User($this->user->request, $this->user->db);
                $userObject->identity($post['user_id']);

                $postObject->author = $userObject;

                $result[] = $postObject;
            }

            return $result;
        }

        /**
         * Получение последних 10 постов или более
         * @param ?int $limit
         * @return array
         */
        public function findLatest($limit = 10)
        {
            return $this->findAll($limit);
        }

        /**
         * Сохранение данных поста либо добавление поста
         * @return bool|mysqli_result
         */
        public function save()
        {
            if ($this->id) {
                $sql = "UPDATE `{$this->tableName}` SET `title`='{$this->title}', `content`='{$this->nl2br($this->content)}' WHERE `id`='{$this->id}'";
                return $this->user->db->query($sql);
            } else {
                $sql = "INSERT INTO `{$this->tableName}` (title, content, user_id) VALUES (?, ?, ?)";
                $stmt = $this->user->db->prepare($sql);
                return $stmt->execute([$this->title, $this->nl2br($this->content), $this->user->id]);
            }
        }

        /**
         * Удаляет пост, а так же все комментарии
         * @return bool|void
         */
        public function deletePost()
        {
            if (((($this->author->id == $this->user->id && $this->comment_count > 0) || $this->author->id !== $this->user->id) && $this->user->isGuest) || ($this->user->isAdmin && $this->user->isBlocked)) return false;

            $sql = "DELETE FROM `{$this->tableName}` WHERE `id`='{$this->id}'";
            $this->user->db->query($sql);
            $this->deleteAllComments();
        }

        /**
         * Создает комментарий для поста
         * @param string $content
         * @param ?int $answer_id
         * @return bool
         */
        public function createComment($content, $answer_id = null)
        {
            $commentObject = new Comment($this->user);
            if ($answer_id)
                return $commentObject->createComment($this->id, $content, $answer_id);
            else
                return $commentObject->createComment($this->id, $content);
        }

        /**
         * Удаление комментария с веткой по id
         * @return bool|void
         */
        public function deleteComment($comment_id)
        {
            if (!$this->user->isAdmin) return false;
            $comment = new Comment($this->user);
            $comment->getComment($this->id, $comment_id);
            $comment->deleteComment();
        }

        /**
         * Удаление всех комментариев текущего экземпляра
         * @return bool|mysqli_result
         */
        public function deleteAllComments()
        {
            if ($this->user->isGuest) return false;
            $sql = "DELETE FROM `comments` WHERE `post_id`='{$this->id}'";
            return $this->user->db->query($sql);
        }
    }