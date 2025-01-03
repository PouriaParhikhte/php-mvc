<?php

namespace Core\Helpers\mysqlClause;

trait Having
{
    protected $sql;

    public function having($column, $operator, $value)
    {
        $this->sql .= !\strpos($this->sql, 'HAVING') ? ' HAVING' : ' AND';
        $this->sql .= " $column $operator $value";
        return $this;
    }
}
