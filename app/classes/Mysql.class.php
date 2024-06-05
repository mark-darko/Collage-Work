<?php
    class Mysql extends mysqli {
        public bool $isConnected;

        /**
         * Конструктор класса
         * @param array $dbConfig
         */
        public function __construct($dbConfig)
        {
            parent::__construct(
                $dbConfig['host'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['database'],
                $dbConfig['port']
            );

            $this->isConnected = $this->connect_errno ? false : true;

            $this->set_charset('utf8');
        }

        /**
         * Получает из бд данные в виде ассоциативного массива
         * @param string $query
         * @return array
         */
        public function queryAssoc($query)
        {
            $result = $this->query($query);

            return $result->fetch_assoc();
        }

        /**
         * Уникально ли значение в бд
         * @param string $table
         * @param string $fiels
         * @param string $value
         * @return bool
         */
        public function isUnique($table, $field, $value)
        {
            $query = "SELECT COUNT(*) FROM `$table` WHERE `$field` = '$value'";
            $result = $this->query($query);
            $row = $result->fetch_row();
            return $row[0] === '0';
        }
    }