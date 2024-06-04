<?php
    class Data {

        /**
         * Валидация данных в классах
         * @return bool
         */
        public function validateData()
        {
            $errors = [];
    
            $objectVars = get_object_vars($this);
    
            foreach ($objectVars as $key => $value) {
                if (strpos($key, 'validate') === 0) {
                    //$validationMethod = 'validate' . ucfirst(substr($key, 8));
    
                    //if (method_exists($this, $validationMethod) && !$this->$validationMethod()) {
                    //    $errors[] = 'Validation error for ' . $key;
                    //}

                    if ($value === true) {
                        $errors[] = 'Validation error for ' . $key;
                    }
                }
            }
    
            return empty($errors);
        }
    
        /**
         * Загрузка данных в классы
         * @param array $data
         */
        public function loadData($data) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        /**
         * Заменяет \n\r на <br/>
         * @param string $data
         * @return string|bool
         */
        public function nl2br($data)
        {
            if (!$data) return false;

            return preg_replace('/\v+|\\\r\\\n/ui','<br/>', $data);
        }
    
        /**
         * Заменяет <br/> на \n\r
         * @param string $data
         * @return string|bool
         */
        public function br2nl($data)
        {
            if (!$data) return false;

            return str_replace('<br/>', "\r\n", $data);
        }
    
        /**
         * Форматирует дату
         * @param string $data
         * @return string|bool
         */
        public function formatDate($date)
        {
            if (!$date) return false;

            $datetime = new DateTime($date);
            return $datetime->format('Y-m-d H:i:s');
        }
        
        /**
         * Возвращает нужно форму слова с числом
         * @param int $number
         * @param string $singular
         * @param string $plural1
         * @param string $plural2
         * @return string|bool
         */
        public function pluralize($number, $singular, $plural1, $plural2) {
            if ($number === null) return false;

            $lastDigit = $number % 10;
            $lastTwoDigits = $number % 100;
          
            if ($lastDigit == 1 && $lastTwoDigits != 11) {
              return $number . ' ' . $singular;
            } elseif ($lastDigit >= 2 && $lastDigit <= 4 && ($lastTwoDigits < 10 || $lastTwoDigits >= 20)) {
              return $number . ' ' . $plural1;
            } else {
              return $number . ' ' . $plural2;
            }
        }          
    }