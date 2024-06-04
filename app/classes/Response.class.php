<?php
    class Response {
        private User $user;
        
        /**
         * Конструктор класса
         * @param User $user
         */
        public function __construct($user)
        {
            $this->user = $user;
    
            if ($this->user->request->get('token') && !isset($this->user->id)) {
                $this->redirect('/app/index.php');
            }
        }
    
        /**
         * Получение ссылки с параметрами
         * @param string $url
         * @param ?array $params
         * @return string
         */
        public function getLink(string $url, $params = [])
        {
            if (isset($this->user->id) && !isset($params['token'])) {
                $params['token'] = $this->user->token;
            }
    
            if (strpos($url, '?') === false && !empty($params)) {
                $url .= '?';
            }
    
            return $url . http_build_query($params);
        }
    
        /**
         * Редирект с параметрами
         * @param string $url
         * @param ?array $params
         */
        public function redirect($url, $params = [])
        {
            header('Location: http://' . $this->user->request->host() . $this->getLink($url, $params));
            exit;
        }
    }