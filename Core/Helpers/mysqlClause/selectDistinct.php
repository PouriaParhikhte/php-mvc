<?php

namespace Core\Helpers\mysqlClause;

trait selectDistinct
{
    protected $sql, $table;

    public function selectDistinct(array $columns)
    {
        $columns = \implode(',', $columns);
        $this->sql = "SELECT DISTINCT $columns FROM $this->table";
        return $this;
    }
}
