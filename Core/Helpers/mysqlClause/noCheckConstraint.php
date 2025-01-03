<?php

namespace Core\Helpers\mysqlClause;

trait NoCheckConstraint
{
    protected $sql;

    public function noCheckConstraint()
    {
        $this->sql .= " NOCHECK CONSTAINT ALL";
        return $this;
    }
}
