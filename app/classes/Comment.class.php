<?php
    class Comment extends Data {
        private string $tableName = 'comments';

        private User $user;

        public int $id;
        public int $post_id;
        public string $content;
        public ?Comment $answer_comment = null;
        public object $author;
        public string $created_at;

        /**
         * Конструктор класса
         * @param User $user
         */
        public function __construct($user)
        {
            $this->user = $user;
        }

        /**
         * Загружает данные в класс с помощью родительского класса
         * @param array $data
         */
        public function load($data)
        {
            $this->loadData($data);

            $this->author = new User($this->user->request, $this->user->db);
            $this->author->identity($data['user_id']);

            if ($data['answer_id']) {
                $this->answer_comment = new static($this->user);
                $this->answer_comment->getComment($data['post_id'], $data['answer_id']);
            }
        }

        /**
         * Создает комментарий для поста
         * @param int $post_id
         * @param string $content
         * @param ?int $answer_id
         * @return bool
         */
        public function createComment($post_id, $content, $answer_id = null)
        {
            if ($this->user->isBlocked || !$content) return false;

            $post = $this->user->db->queryAssoc("SELECT `user_id` FROM `posts` WHERE `id`='{$post_id}'");

            if (!$post || ($post['user_id'] == $this->user->id && !$answer_id) || ($post['user_id'] != $this->user->id && $answer_id)) return false;

            $sql = "INSERT INTO `{$this->tableName}` (post_id, content, answer_id, user_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->user->db->prepare($sql);

            if ($answer_id) {
                $answer_comment = $this->user->db->queryAssoc("SELECT `id` FROM `{$this->tableName}` WHERE `id`='{$answer_id}'");
                if ($answer_comment)
                    return $stmt->execute([$post_id, $this->nl2br($content), $answer_id, $this->user->id]);
                else
                    return false;
            } else {
                return $stmt->execute([$post_id, $this->nl2br($content), null, $this->user->id]);
            }
        }

        /**
         * Получает данные комментария и загружает его
         * @param int $post_id
         * @param int $comment_id
         * @return void|bool
         */
        public function getComment($post_id, $comment_id)
        {
            $comment = $this->user->db->queryAssoc("SELECT * FROM `{$this->tableName}` WHERE `id`='{$comment_id}' AND `post_id`='{$post_id}'");
            if ($comment)
                $this->load($comment);
            else
                return false;
        }

        /**
         * Получить комменатрия текущего поста
         * @param int $post_id
         * @return array
         */
        public function getComments($post_id)
        {
            $query = "SELECT `post_id`,`id`
            FROM `{$this->tableName}`
            WHERE `post_id` = '{$post_id}'
            ORDER BY `id` DESC";

            $comments = $this->user->db->query($query)->fetch_all() ?? [];

            $result = [];
            foreach ($comments as $comment_id) {
                $commentObject = new Comment($this->user);
                $commentObject->getComment($comment_id[0],$comment_id[1]);

                $result[] = $commentObject;
            }

            return $result;
        }

        /**
         * Удаление комментария с веткой по id
         * @return bool|void
         */
        public function deleteComment()
        {
            if ($this->user->isBlocked || $this->user->isGuest) return false;

            $comment_answer_id = $this->user->db->queryAssoc("SELECT `post_id`,`id` FROM `{$this->tableName}` WHERE `answer_id`='{$this->id}'");

            if ($comment_answer_id) {
                $comment = new static($this->user);
                $comment->getComment($comment_answer_id['post_id'], $comment_answer_id['id']);
                $comment->deleteComment();
            }
            
            $sql = "DELETE FROM `comments` WHERE `id`='{$this->id}'";
            $this->user->db->query($sql);
        }
    }