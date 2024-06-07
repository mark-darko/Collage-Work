<?php
    class Validator {

        /**
         * Валидация данных
         * @param object $data
         * @param array $validationRules
         */
        public static function make($data, $validationRules)
        {
            $errors = [];
            foreach ($validationRules as $field => $rules) {
                $filedError = 'validate_' . $field . '_error';
                foreach ($rules as $rule => $value) {
                    switch ($rule) {
                        case 'required':
                            if (empty($data->$field)) {
                                $data->$filedError[] = "Поле обязательно для заполнения";
                            }
                            break;
                        case 'min':
                            if (strlen($data->$field) < $value) {
                                $data->$filedError[] = "Поле должно содержать не менее {$value} символов";
                            }
                            break;
                        case 'max':
                            if (strlen($data->$field) > $value) {
                                $data->$filedError[] = "Поле должно содержать не более {$value} символов";
                            }
                            break;
                        case 'email':
                            //if (!filter_var($this->$field, FILTER_VALIDATE_EMAIL)) {
                            //    $this->$filedError[] = "Поле {$field} должно содержать действительный адрес электронной почты";
                            //}
                            if (!preg_match("/^[._a-zA-Z0-9-]+@[.a-zA-Z0-9-]+.[a-z]{2,6}$/", $data->$field))
                                $data->$filedError[] = "Поле должно содержать действительный адрес электронной почты";

                            list($username, $domain) = explode("@",$data->$field);

                            if (!checkdnsrr($domain))
                                $data->$filedError[] = "Поле должно содержать действительный адрес электронной почты";

                            break;
                        case 'unique':
                            if (!$data->db->isUnique($data->tableName, $value, $data->$field)) {
                                $data->$filedError[] = "Пользователь с такими данными уже существует";
                            }
                            break;
                        case 'matches':
                            if ($data->$field != $data->$value) {
                                $data->$filedError[] = "Пароли не совпадают";
                            }
                            break;
                        case 'file_size_max':
                            if (!isset($_FILES[$field]) && $_FILES['field']['error'] !== UPLOAD_ERR_OK && $_FILES['uploadedFile']['size'] > $value)
                                $data->$filedError[] = "Размер файла должен быть не более $value байт";
                            break;
                        case 'file_types':
                            if (isset($_FILES[$field]) && $_FILES['field']['error'] == UPLOAD_ERR_OK) {
                                $fileName = $_FILES['uploadedFile']['name'];
                                $fileNameCmps = explode(".", $fileName);
                                $fileExtension = strtolower(end($fileNameCmps));

                                $allowedfileExtensions = explode('|', $value);
                                if (!in_array($fileExtension, $allowedfileExtensions)) {
                                    $data->$filedError[] = "Тип файла должен быть " . str_replace('|', ',', $value);;   
                                }
                            }
                            break;
                    }
                }
            }
        }
    }