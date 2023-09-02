<?php

namespace php\models\repository;

use PDO;
use php\services\Database;

abstract class Repository
{
    protected PDO $db;
    protected string $tableName;

    public function __construct(PDO|null $db = null)
    {
        $this->db = $db ?: Database::getPDO();
    }

    public function getByColumn(string $columnName, $columnValue, array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName WHERE $columnName = :columnValue");
        $stmt->bindParam(':columnValue', $columnValue, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllById(array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllByColumn(string $columnName, array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName WHERE $columnName = :columnValue");
        $stmt->bindParam(':columnValue', $columnValue, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);

        foreach ($data as $column => $value) {
            $stmt->bindParam(':' . $column, $value);
        }

        return $stmt->execute();
    }


    public function deleteById(int $id): bool
    {
        $query = "DELETE FROM $this->tableName WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteByColumn(string $columnName, $columnValue): bool
    {
        $stmt = $this->db->prepare("DELETE FROM $this->tableName WHERE $columnName = :columnValue");
        $stmt->bindParam(':columnValue', $columnValue);
        return $stmt->execute();
    }
}
