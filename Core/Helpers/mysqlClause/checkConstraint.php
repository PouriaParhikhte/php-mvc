<?php

namespace Core\Helpers\mysqlClause;

trait CheckConstraint
{
    protected $sql;

    public function checkConstraint()
    {
        $this->sql .= " CHECK CONSTAINT ALL";
        return $this;
    }
}
