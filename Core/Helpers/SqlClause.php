<?php

namespace Core\Helpers;

use Core\Model;

class SqlClause extends Model
{
    public function selectDistinct(array $columns)
    {
        $columns = \implode(',', $columns);
        $this->sql->query = "SELECT DISTINCT $columns FROM $this->table";
        return $this;
    }

    public function escape($scapeClause)
    {
        $this->sql->query .= " ESCAPE '$scapeClause'";
        return $this;
    }

    public function union(): void
    {
        $this->sql->query .= " UNION ";
    }

    public function as(string $name)
    {
        $this->sql->query .= " AS $name";
        return $this;
    }

    public function having($column, $operator, $value)
    {
        $this->sql->query .= !\strpos($this->sql->query, 'HAVING') ? ' HAVING' : ' AND';
        $this->sql->query .= " $column $operator $value";
        return $this;
    }

    public function dateSub($intervalValue, $interval)
    {
        $this->sql->query .= (strpos($this->sql->query, 'WHERE')) ? ' AND' : '';
        $this->sql->query .= " updatedAt < DATE_SUB(now(),
         INTERVAL $intervalValue $interval)";
        return $this;
    }

    public function lastInsertId(): mixed
    {
        $this->sql->query = "SELECT LAST_INSERT_ID() FROM `$this->table`";
        return $this;
    }

    public function noCheckConstraint()
    {
        $this->sql->query .= " NOCHECK CONSTAINT ALL";
        return $this;
    }

    public function checkConstraint()
    {
        $this->sql->query .= " CHECK CONSTAINT ALL";
        return $this;
    }

    public function disableForeignKeyCheck()
    {
        $this->sql->query = "SET FOREIGN_KEY_CHECKS=0;";
        return $this;
    }

    public function enableForeignKeyCheck()
    {
        $this->sql->query = "SET FOREIGN_KEY_CHECKS=1;";
        return $this;
    }

    public function concatOnUpdate($columnName,  $input)
    {
        $this->sql->query .= " ON DUPLICATE KEY UPDATE `$columnName` =
         CONCAT('$input',$columnName)";
        return $this;
    }
}
