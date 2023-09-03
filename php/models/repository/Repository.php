<?php

namespace php\models\repository;

use PDO;
use php\helpers\Collection;
use php\models\entities\Model;
use php\services\Database;

abstract class Repository
{
    protected PDO $db;
    protected string $tableName;
    protected string $modelClass;
    private bool $returnAsArray;

    public function __construct(PDO|null $db = null)
    {
        $this->db = $db ?: Database::getPDO();
        $this->returnAsArray = false;
    }

    /**
     * By default, methods that return one or more entities group this data in a Collection. O
     * asArray() method forces the return to be the data grouped in an array instead
     * 
     * @param $decision
     * @return static
     */
    public function returnAsArray(bool $decision = true)
    {
        $this->returnAsArray = $decision;
        return $this;
    }

    /**
     * @param mixed $data
     * @return array|mixed|false|Collection<string, Model>
     */
    private function returnMultiples(mixed $data)
    {
        if (!is_array($data))
            return $data;
        return $this->returnAsArray
            ? $data
            : new Collection(array_map(fn ($v) => $this->modelClass::fromArray($v), $data));
    }

    /**
     * @param mixed $data
     * @return array|mixed|false|Model
     */
    private function returnSimple(mixed $data)
    {
        if (!is_array($data))
            return $data;
        return $this->returnAsArray
            ? $data
            : $this->modelClass::fromArray($data);
    }

    /**
     * @param string $columnName
     * @param mixed $columnValue
     * @param array $columns
     * @return array|mixed|false|Collection<string, Model>
     */
    public function getByColumn(string $columnName, $columnValue, array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName WHERE $columnName = :columnValue");
        $stmt->bindParam(':columnValue', $columnValue, PDO::PARAM_STR);
        $stmt->execute();
        return $this->returnMultiples($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @param int $id
     * @param array $columns
     * @return array|mixed|false|Model
     */
    public function getById(int $id, array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $this->returnSimple($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * @param array $columns
     * @return array|mixed|false|Collection<string, Model>
     */
    public function getAllById(array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName");
        $stmt->execute();
        return $this->returnMultiples($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * @param string $columnName
     * @param array $columns
     * @return array|mixed|false|Collection<string, Model>
     */
    public function getAllByColumn(string $columnName, array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName WHERE $columnName = :columnValue");
        $stmt->bindParam(':columnValue', $columnValue, PDO::PARAM_STR);
        $stmt->execute();
        return $this->returnMultiples($stmt->fetchAll(PDO::FETCH_ASSOC));
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

    public function getAllManyToMany(
        string $relatedTable,
        string $joinTable,
        string|null $joinCondition1 = null,
        string|null $joinCondition2 = null,
        int|null $parentId = null,
        array $columns = []
    ): array {
        $selectedColumns = [];

        foreach ($columns as $column) {
            // Prefixar as colunas com o nome da tabela
            $selectedColumns[] = "$relatedTable.$column as {$relatedTable}_$column";
        }

        $selectedColumns = empty($selectedColumns) ? '*' : implode(', ', $selectedColumns);

        $joinCondition1 = $joinCondition1 ?: "$this->tableName.id = $joinTable.{$this->tableName}_id";
        $joinCondition2 = $joinCondition2 ?: "$relatedTable.id = $joinTable.{$relatedTable}_id";
        $filterParentCondition = is_null($parentId) ? '' : "WHERE $this->tableName.id = :parentId";

        $query = "SELECT $selectedColumns FROM $this->tableName
                  JOIN $joinTable ON $joinCondition1
                  JOIN $relatedTable ON $joinCondition2
                  $filterParentCondition";

        $stmt = $this->db->prepare($query);
        if (!is_null($parentId))
            $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
