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
        }

        public function queryAssoc(string $query): array
        {
            $result = $this->query($query);
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }

        public function isUnique(string $table, string $field, string $value): bool
        {
            $query = "SELECT COUNT(*) FROM $table WHERE $field = '$value'";
            $result = $this->query($query);
            $row = $result->fetch_row();
            return $row[0] === '0';
        }
    }