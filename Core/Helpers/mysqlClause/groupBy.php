<?php

namespace Core\Helpers\mysqlClause;

trait GroupBy
{
    protected $sql;

    public function groupBy($column)
    {
        $this->sql .= " GROUP BY $column";
        return $this;
    }
}
