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
    }