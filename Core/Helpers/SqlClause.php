<?php

namespace Core\Helpers;

use Core\Model;

class SqlClause extends Model
{
    public function selectDistinct(array $columns)
    {
        $columns = \implode(',', $columns);
        $this->sql = "SELECT DISTINCT $columns FROM $this->table";
        return $this;
    }

    public function escape($scapeClause)
    {
        $this->sql .= " ESCAPE '$scapeClause'";
        return $this;
    }

    public function union(): void
    {
        $this->sql .= " UNION ";
    }

    public function as(string $name)
    {
        $this->sql .= " AS $name";
        return $this;
    }

    public function having($column, $operator, $value)
    {
        $this->sql .= !\strpos($this->sql, 'HAVING') ? ' HAVING' : ' AND';
        $this->sql .= " $column $operator $value";
        return $this;
    }

    public function dateSub($intervalValue, $interval)
    {
        $this->sql .= (strpos($this->sql, 'WHERE')) ? ' AND' : '';
        $this->sql .= " updatedAt < DATE_SUB(now(), INTERVAL $intervalValue $interval)";
        return $this;
    }

    public function lastInsertId(): mixed
    {
        $this->sql = "SELECT LAST_INSERT_ID() FROM `$this->table`";
        return $this->getResult();
    }

    public function noCheckConstraint()
    {
        $this->sql .= " NOCHECK CONSTAINT ALL";
        return $this;
    }

    public function checkConstraint()
    {
        $this->sql .= " CHECK CONSTAINT ALL";
        return $this;
    }

    public function disableForeignKeyCheck()
    {
        $this->sql = "SET FOREIGN_KEY_CHECKS=0;";
        return $this;
    }

    public function enableForeignKeyCheck()
    {
        $this->sql = "SET FOREIGN_KEY_CHECKS=1;";
        return $this;
    }

    public function concatOnUpdate($columnName,  $input)
    {
        $this->sql .= " ON DUPLICATE KEY UPDATE `$columnName` = CONCAT('$input',$columnName)";
        return $this;
    }
}
