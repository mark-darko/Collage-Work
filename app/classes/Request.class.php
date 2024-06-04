<?php
    class Request
    {
        public bool $isPost;
        public bool $isGet;
    
        /**
         * Конструктор класса
         */
        public function __construct()
        {
            $this->isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
            $this->isGet = $_SERVER['REQUEST_METHOD'] === 'GET';
        }
    
        /**
         * Метод "очистки" вхождения
         * @param string $input
         * @return bool|string
         */
        public function cleanInput($input)
        {
            if (!$input) return false;
            
            //if (is_array($input)) {
            //    return $this->cleanArray($input);
            //} else {
            return htmlspecialchars(strip_tags($input));
            //}
        }
    
        /**
         * Метод очистки входного массива
         * @param array $array
         * @return array
         */
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
    
        /**
         * Получение значения или array из $_POST с очисткой
         * @param ?string $key
         * @return string|array|bool
         */
        public function post($key = null)
        {
            if ($key) {
                if (!isset($_POST[$key])) return false;
                return $this->cleanInput($_POST[$key]);
            } else
                return $this->cleanArray($_POST);
        }
    
        /**
         * Получение значения или array из $_POST с очисткой
         * @param ?string $key
         * @return string|array|bool
         */
        public function get($key = null)
        {
            if ($key) {
                if (!isset($_GET[$key])) return false;
                return $this->cleanInput($_GET[$key]);
            } else
                return $this->cleanArray($_GET);
        }

        /**
         * Получение адреса хоста
         * @return string
         */
        public function host()
        {
            return $_SERVER['HTTP_HOST'];
        }
    
        /**
         * Получение токена из $_GET
         * @return ?string
         */
        public function token()
        {
            $token = $this->get('token');
            return $token ? $this->cleanInput($token) : null;
        }
    }    