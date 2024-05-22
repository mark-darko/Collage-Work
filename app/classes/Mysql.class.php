<?php
    class Mysql extends mysqli {
        public $isConnected;

        public function __construct(array $dbConfig)
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

        public function queryAssoc(string $query)
        {
            $result = $this->query($query);

            return $result->fetch_assoc();
        }

        public function isUnique(string $table, string $field, string $value): bool
        {
            $query = "SELECT COUNT(*) FROM $table WHERE $field = '$value'";
            $result = $this->query($query);
            $row = $result->fetch_row();
            return $row[0] === '0';
        }
    }