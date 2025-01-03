<?php

namespace Core\Helpers\mysqlClause;

trait DateSub
{
    protected $sql;

    public function dateSub($intervalValue, $interval)
    {
        $this->sql .= (strpos($this->sql, 'WHERE')) ? ' AND' : '';
        $this->sql .= " updatedAt < DATE_SUB(now(), INTERVAL $intervalValue $interval)";
        return $this;
    }
}
