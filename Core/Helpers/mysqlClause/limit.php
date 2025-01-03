<?php

namespace Core\Helpers\mysqlClause;

trait Limit
{
    protected $sql;

    public function limit($value)
    {
        $this->sql .= " LIMIT ?";
        $this->values[] = $value;
        return $this;
    }

    public function first(int $toArray = null): mixed
    {
        return $this->limit(1)->getResult($toArray)[0] ?? null;
    }
}
