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
    private bool $forceArrayReturn;

    public function __construct(PDO|null $db = null)
    {
        $this->db = $db ?: Database::getPDO();
        $this->forceArrayReturn = false;
    }

    /**
     * By default, methods that return one or more entities group this data in a Collection. O
     * asArray() method forces the return to be the data grouped in an array instead
     * 
     * @param $decision
     * @return static
     */
    public function forceArrayReturn(bool $decision = true)
    {
        $this->forceArrayReturn = $decision;
        return $this;
    }

    /**
     * @param mixed $data
     * @return array|mixed|false|Collection<string, Model>
     */
    protected function choseManyReturn(mixed $data)
    {
        if (is_array($data))
            return $data;
        if ($this->forceArrayReturn) {
            if (is_object($data) && method_exists($data, 'toArray'))
                return $data->toArray();
            return (array) $data;
        }
        return $data;
    }

    /**
     * @param mixed $data
     * @return array|mixed|false|Model
     */
    protected function choseOneReturn(mixed $data)
    {
        if (!is_array($data))
            return $data;
        return $this->forceArrayReturn
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        return $stmt->fetch(PDO::FETCH_ASSOC);
        // return $this->choseOneReturn($stmt->fetch(PDO::FETCH_ASSOC));
    }

    /**
     * @param array $columns
     * @return array|mixed|false|Collection<string, Model>
     */
    public function getAll(array $columns = [])
    {
        $columnList = empty($columns) ? '*' : implode(', ', $columns);
        $stmt = $this->db->prepare("SELECT $columnList FROM $this->tableName");
        $stmt->execute();
        return $this->choseManyReturn($stmt->fetchAll(PDO::FETCH_ASSOC));
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
        return $this->choseManyReturn($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function save(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($query);
        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
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

    /**
     * Prefix the columns with the table names to avoid collisions and overlapping columns
     * @param string $table
     * @param array $columns
     * @return string
     */
    protected function prefixColumns(string $table, array $columns)
    {
        $separator = DATABASE_TABLE_COLUMN_SEPARATOR;
        return empty($columns) ?
            "$table.*"
            : implode(', ', array_map(fn ($c) => "$table.$c as {$table}{$separator}$c", $columns));
    }

    /**
     * Removes the column prefixes that were added in the 'prefixColumns()' method
     * @return array
     */
    protected function removeColumnsPrefix($table, $columns)
    {
        $prefix = $table . DATABASE_TABLE_COLUMN_SEPARATOR;
        return array_map_assoc(
            fn ($k, $v) => str_starts_with($k, $prefix)
                ? [substr($k, strlen($prefix)) => $v]
                : [$k => $v],
            $columns
        );
    }

    public function getAllManyToManyById(
        string $relatedTable,
        string $joinTable,
        string|null $joinCondition1 = null,
        string|null $joinCondition2 = null,
        int|null $parentId = null,
        array $parentColumns = [],
        array $relatedColumns = []
    ): array {
        $parentColumns = $this->prefixColumns($this->tableName, $parentColumns);
        $relatedColumns = $this->prefixColumns($relatedTable, $relatedColumns);

        $joinCondition1 = $joinCondition1 ?: "$this->tableName.id = $joinTable.{$this->tableName}_id";
        $joinCondition2 = $joinCondition2 ?: "$relatedTable.id = $joinTable.{$relatedTable}_id";
        $filterParentCondition = is_null($parentId) ? '' : "WHERE $this->tableName.id = :parentId";

        $query = "SELECT $parentColumns, $relatedColumns FROM $this->tableName
                  JOIN $joinTable ON $joinCondition1
                  JOIN $relatedTable ON $joinCondition2
                  $filterParentCondition";

        $stmt = $this->db->prepare($query);
        if (!is_null($parentId))
            $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllManyToManyWithCondition(
        string $relatedTable,
        string $joinTable,
        string $joinCondition1,
        string $joinCondition2,
        string $condition = '',
        array $conditionValues = [],
        array $parentColumns = [],
        array $relatedColumns = []
    ): array {
        $parentColumns = $this->prefixColumns($this->tableName, $parentColumns);
        $relatedColumns = $this->prefixColumns($relatedTable, $relatedColumns);

        $query = "SELECT $parentColumns, $relatedColumns FROM $this->tableName
                  JOIN $joinTable ON $joinCondition1
                  JOIN $relatedTable ON $joinCondition2";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        $stmt = $this->db->prepare($query);
        foreach ($conditionValues as $paramName => $paramValue) {
            $paramType = is_int($paramValue) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($paramName, $paramValue, $paramType);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWithJoin(
        string $relatedTable,
        string $joinCondition,
        int $parentId,
        array $parentColumns = [],
        array $relatedColumns = []
    ): array {
        $parentColumns = $this->prefixColumns($this->tableName, $parentColumns);
        $relatedColumns = $this->prefixColumns($relatedTable, $relatedColumns);
        $query = "SELECT $parentColumns, $relatedColumns FROM $this->tableName
                  LEFT JOIN $relatedTable ON $joinCondition
                  WHERE $this->tableName.id = :parentId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':parentId', $parentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithJoin(
        string $relatedTable,
        string $joinCondition,
        string $condition = '',
        array $conditionValues = [],
        array $parentColumns = [],
        array $relatedColumns = []
    ): array {
        $parentColumns = $this->prefixColumns($this->tableName, $parentColumns);
        $relatedTable = $this->prefixColumns($relatedTable, $relatedColumns);
        $query = "SELECT $parentColumns, $relatedColumns FROM $this->tableName
                  LEFT JOIN $relatedTable ON $joinCondition";
        if (!empty($condition))
            $query .= " WHERE $condition";
        $stmt = $this->db->query($query);
        foreach ($conditionValues as $paramName => $paramValue) {
            $paramType = is_int($paramValue) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($paramName, $paramValue, $paramType);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(array $data, string $whereClause): bool
    {
        if (empty($data) || empty($whereClause)) {
            return false;
        }
        $setClause = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = :" . $column;
        }
        $setClause = implode(', ', $setClause);

        $query = "UPDATE $this->tableName SET $setClause WHERE $whereClause";
        $stmt = $this->db->prepare($query);

        foreach ($data as $column => $value) {
            $stmt->bindValue(':' . $column, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        return $stmt->execute();
    }

    public function getAllManyToManySortedWithCondition(
        string $relatedTable,
        string $joinTable,
        string $joinCondition1,
        string $joinCondition2,
        string $condition = '',
        array $conditionValues = [],
        array $parentColumns = [],
        array $relatedColumns = [],
        string $orderByColumn = '',
        string $orderByDirection = 'ASC'
    ): array {
        $parentColumns = $this->prefixColumns($this->tableName, $parentColumns);
        $relatedColumns = $this->prefixColumns($relatedTable, $relatedColumns);
    
        $query = "SELECT $parentColumns, $relatedColumns FROM $this->tableName
                  JOIN $joinTable ON $joinCondition1
                  JOIN $relatedTable ON $joinCondition2";
    
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
    
        if (!empty($orderByColumn)) {
            $query .= " ORDER BY $orderByColumn $orderByDirection";
        }
    
        $stmt = $this->db->prepare($query);
    
        foreach ($conditionValues as $paramName => $paramValue) {
            $paramType = is_int($paramValue) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindParam($paramName, $paramValue, $paramType);
        }

        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
