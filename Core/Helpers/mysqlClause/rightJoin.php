<?php

namespace Core\Helpers\mysqlClause;

trait rightJoin
{
    protected $sql;

    public function rightJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql .= ' RIGHT JOIN';
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }
}
