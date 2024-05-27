<?php
    class Data {
        public function validateData() {
            $errors = [];
    
            $objectVars = get_object_vars($this);
    
            foreach ($objectVars as $key => $value) {
                if (strpos($key, 'validate') === 0) {
                    $validationMethod = 'validate' . ucfirst(substr($key, 8));
    
                    if (method_exists($this, $validationMethod) && !$this->$validationMethod()) {
                        $errors[] = 'Validation error for ' . $key;
                    }
                }
            }
    
            return empty($errors);
        }
    
        public function loadData($data) {
            foreach ($data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        public function nl2br($data) {
            return preg_replace('/\v+|\\\r\\\n/ui','<br/>', $data);
        }
    
        public function br2nl($data) {
            return str_replace('<br/>', "\r\n", $data);
        }
    
        public function formatDate($date) {
            $datetime = new DateTime($date);
            return $datetime->format('d.mY H:i:s');
        }    
    }