<?php

namespace Core\Helpers\mysqlClause;

trait joinCondition
{
    protected $sql;

    private function joinCondition($tableName, $foreignKey = null, $ownerKey = null)
    {
        $this->sql .= ($ownerKey !== null) ? " `$tableName` ON $this->table.$foreignKey = $tableName.$ownerKey" : " `$tableName` USING($foreignKey)";
        return $this;
    }
}
