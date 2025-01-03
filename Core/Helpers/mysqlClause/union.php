<?php

namespace Core\Helpers\mysqlClause;

trait union
{
    protected $sql;

    public function union(): void
    {
        $this->sql .= " UNION ";
    }
}
