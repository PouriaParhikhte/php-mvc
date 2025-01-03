<?php

namespace Core\Helpers\mysqlClause;

trait InnerJoin
{
    use joinCondition;
    protected $sql;

    public function innerJoin($tableName, $foreignKey, $ownerKey = null)
    {
        $this->sql .= " INNER JOIN";
        $this->joinCondition($tableName, $foreignKey, $ownerKey);
        return $this;
    }
}
