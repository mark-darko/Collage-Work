<?php
    class Response {
        private $user;
    
        public function __construct(User $user) {
            $this->user = $user;
    
            if ($this->user->request->get('token') && !isset($this->user->id)) {
                $this->redirect('/');
            }
        }
    
        public function getLink($url, $params = []) {
            if (isset($this->user->id) && !isset($params['token'])) {
                $params['token'] = $this->user->token;
            }
    
            if (strpos($url, '?') === false && !empty($params)) {
                $url .= '?';
            }
    
            return $url . http_build_query($params);
        }
    
        public function redirect($url, $params = []) {
            header('Location: http://' . $this->user->request->host() . $this->getLink($url, $params));
            exit;
        }
    }