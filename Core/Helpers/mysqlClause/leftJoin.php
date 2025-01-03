<?php

namespace Core\Helpers\mysqlClause;

trait LeftJoin
{
    use joinCondition;
    protected $sql;

    public function leftJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql .= ' LEFT JOIN';
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }
}
