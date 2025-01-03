<?php

namespace Core\Helpers\mysqlClause;

trait OrderBy
{
    protected $sql;

    public function orderBy($column, $sort = 'ASC')
    {
        $this->sql .= " ORDER BY $column $sort";
        return $this;
    }
}
