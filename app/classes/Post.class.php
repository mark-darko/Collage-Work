<?php
    class Post extends Data {
        private $user;

        private $tableName = 'posts';
        public $id;
        //public $preview_text;
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
                $user = new User($this->user->request, $this->user->db);
                $user->identity($post["user_id"]);

                $this->load(array_merge($post, $this->user->db->queryAssoc("SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = '{$id}'"), ["author" => $user]));

                return true;
            } else return false;       
        }

        public function getComments()
        {
            $query = "SELECT comments.* 
            FROM posts 
            JOIN comments ON posts.id = comments.post_id
            WHERE posts.id = '{$this->id}'
            ORDER BY comments.created_at DESC";

            $comments = $this->user->db->query($query)->fetch_all(MYSQLI_ASSOC);

            foreach($comments as $key => $comment) {
                if ($comment['answer_id']) {
                    $id = array_search($comment['answer_id'], array_column($comments, 'id'));
                    unset($comment['answer_id']);
                    $comments[$id]["answers"][] = $comment;
                    unset($comments[$key]);
                } else {
                    unset($comments[$key]["answer_id"]);
                }
            }

            return $comments;
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
                //$sql = "UPDATE {$this->tableName} SET preview_text='{$this->preview_text}', title='{$this->title}', content='{$this->content}' WHERE id={$this->id}";
                $sql = "UPDATE {$this->tableName} SET title='{$this->title}', content='{$this->nl2br($this->content)}' WHERE id={$this->id}";
                $this->user->db->query($sql);
            } else {
                $sql = "INSERT INTO {$this->tableName} (title, content, user_id) VALUES (?, ?, ?)";
                $stmt = $this->user->db->prepare($sql);
                return $stmt->execute([$this->title, $this->nl2br($this->content), $this->user->id]);
            }
        }
    }