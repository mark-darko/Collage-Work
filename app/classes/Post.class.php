<?php
    class Post extends Data {
        private $user;

        private $tableName = 'posts';
        public $id;
        public $preview_text;
        public $title;
        public $content;
        public $user_id;
        public $created_at;
        public $comment_count;
        public $author;

        public function __construct(User $user)
        {
            $this->user = $user;
        }

        public function validate()
        {

        }

        public function load($data)
        {
            $this->loadData($data);
        }

        public function findOne($id)
        {
            $post = $this->user->db->queryAssoc("SELECT * FROM {$this->tableName} WHERE id = '{$id}'");
            if ($post) {
                $this->load($post);
                $this->load($this->user->db->queryAssoc("SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = '{$id}'"));

                return true;
            } else return false;       
        }

        public function displayDate($date)
        {
            return $this->formatDate($date);
        }

        public function findAll($limit = null)
        {
            $query = "SELECT posts.*, COUNT(comments.id) AS comment_count 
            FROM posts 
            LEFT JOIN comments ON posts.id = comments.post_id 
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

        public function findLatest(int $limit = 10)
        {
            return $this->findAll($limit);
        }

        public function save()
        {
            if ($this->id) {
                $sql = "UPDATE {$this->tableName} SET preview_text='{$this->preview_text}', title='{$this->title}', content='{$this->content}' WHERE id={$this->id}";
                $this->user->db->query($sql);
            } else {
                $sql = "INSERT INTO {$this->tableName} (preview_text, title, content, user_id) VALUES (?, ?, ?, ?)";
                $stmt = $this->user->db->prepare($sql);
                return $stmt->execute([$this->preview_text, $this->title, $this->nl2br($this->content), $this->user->id]);
            }
        }
    }