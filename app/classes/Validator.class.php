<?php
    class Validator {

        /**
         * Валидация данных
         * @param object $data
         * @param array $validationRules
         * @return array|bool
         */
        public static function make($data, $validationRules)
        {
            foreach ($validationRules as $field => $rules) {
                foreach ($rules as $rule => $value) {
                    switch ($rule) {
                        case 'required':
                            if (empty($data->$field)) {
                                $errors[$field][] = "Поле {$field} обязательно для заполнения";
                            }
                            break;
                        case 'min':
                            if (strlen($data->$field) < $value) {
                                $errors[$field][] = "Поле {$field} должно содержать не менее {$value} символов";
                            }
                            break;
                        case 'max':
                            if (strlen($data->$field) > $value) {
                                $errors[$field][] = "Поле {$field} должно содержать не более {$value} символов";
                            }
                            break;
                        case 'email':
                            if (!filter_var($data->$field, FILTER_VALIDATE_EMAIL)) {
                                $errors[$field][] = "Поле {$field} должно содержать действительный адрес электронной почты";
                            }
                            break;
                        case 'unique':
                            if (!$data->db->isUnique($data->tableName, $value, $data->$field)) {
                                $errors[$field][] = "Пользователь с такими данными уже существует";
                            }
                            break;
                        case 'matches':
                            if ($data->$field != $data->$value) {
                                $errors[$field][] = "Пароли не совпадают";
                            }
                            break;
                    }
                }
            }
    
            if ($errors)
                return $errors;
            else
                return false;
        }
    }