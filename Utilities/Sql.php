<?php

namespace TelegramBot\Utilities;

use Exception;
use PDO;

class Sql
{
    private $connection;
    private $dbName;

    public function __construct()
    {
        $this->dbName = env::var('DB_NAME');
        $this->connection = new PDO('mysql:host='.env::var('DB_HOST').';dbname='.$this->dbName, env::var('DB_USER'), env::var('DB_PASS'));
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function InsertInto(string $table, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        try {
            $sql = "INSERT INTO {$table} (";
            foreach ($data as $key => $value) {
                $sql .= $key;
                if (array_key_last($data) != $key) {
                    $sql .= ', ';
                }
            }
            $sql .= ') VALUES (';
            foreach ($data as $key => $value) {
                $sql .= ':'.$key;
                if (array_key_last($data) != $key) {
                    $sql .= ', ';
                }
            }
            $sql .= ')';
            $query = $this->connection->prepare($sql);
            foreach ($data as $key => $value) {
                $query->bindParam(':'.$key, ${$key});
            }
            foreach ($data as $key => $value) {
                ${$key} = $value;
            }

            $query->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function selectFieldsFrom(string $table , array $fields = null , array $where = null) : array
    {
        $sql = "SELECT ";
        if($fields == null) $sql .= '*';
        else{
            foreach ($fields as $key => $value) {
                $sql .= $key;
                if (array_key_last($fields) != $key) $sql .= ', ';
            }
        }
        $sql .= " FROM {$table}";
        if($where != null){
            $sql .= " WHERE ";
            foreach ($where as $key => $value) {
                $sql .= "{$key}=";
                if(is_string($value)) $sql .= "'{$value}'";
                else $sql .= "{$value}";
                if (array_key_last($where) != $key) $sql .= ' AND ';
            }
        }
        $query = $this->connection->prepare($sql);
        $query->execute();
        $query->setFetchMode(PDO::FETCH_ASSOC);
        return $query->fetchAll();
    }
}
