<?php

namespace Core\Helpers\mysqlClause;

trait AsAlias
{
    protected $sql;

    public function as(string $name)
    {
        $this->sql .= " AS $name";
        return $this;
    }
}
