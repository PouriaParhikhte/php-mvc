<?php

namespace Core\Helpers\mysqlClause;

trait escape
{
    protected $sql;

    public function escape($scapeClause)
    {
        $this->sql .= " ESCAPE '$scapeClause'";
        return $this;
    }
}
