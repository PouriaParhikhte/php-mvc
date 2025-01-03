<?php

namespace Core\Helpers\mysqlClause;

trait LeftOuterJoin
{
    use joinCondition;
    protected $sql;

    public function leftOuterJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql .= ' LEFT OUTER JOIN';
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }
}
