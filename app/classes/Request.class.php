<?php
    class Request
    {
        public $isPost;
        public $isGet;
    
        public function __construct()
        {
            $this->isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
            $this->isGet = $_SERVER['REQUEST_METHOD'] === 'GET';
        }
    
        public function cleanInput($input)
        {
            if (is_array($input)) {
                return $this->cleanArray($input);
            } else {
                return htmlspecialchars(strip_tags($input));
            }
        }
    
        public function cleanArray($array)
        {
            return array_map(function ($value) {
                if (is_array($value)) {
                    return $this->cleanArray($value);
                } else {
                    return htmlspecialchars(strip_tags($value));
                }
            }, $array);
        }
    
        public function post($key = null)
        {
            if ($key === null) {
                return $this->cleanArray($_POST);
            } else {
                return $this->cleanInput($_POST[$key] ?? null);
            }
        }
    
        public function get($key = null)
        {
            if ($key === null) {
                return $this->cleanArray($_GET);
            } else {
                return $this->cleanInput($_GET[$key] ?? null);
            }
        }
    
        public function host()
        {
            return $_SERVER['HTTP_HOST'];
        }
    
        public function token()
        {
            $token = $this->get('token');
            return $token ? $this->cleanInput($token) : null;
        }
    }    