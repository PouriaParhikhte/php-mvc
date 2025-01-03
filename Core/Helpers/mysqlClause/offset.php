<?php

namespace Core\Helpers\mysqlClause;

trait Offset
{
    protected $sql;

    public function offset($value)
    {
        $this->sql .= ",?";
        $this->values[] = $value;
        return $this;
    }
}
