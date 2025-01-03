<?php

namespace Core\Helpers\mysqlClause;

trait crossJoin
{
    use joinCondition;
    protected $sql;

    public function crossJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql .= " t1 CROSS JOIN";
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }
}
