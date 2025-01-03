<?php

namespace Core\Helpers\mysqlClause;

trait ConcatOnUpdate
{
    protected $sql;

    public function concatOnUpdate($columnName,  $input)
    {
        $this->sql .= " ON DUPLICATE KEY UPDATE `$columnName` = CONCAT('$input',$columnName)";
        return $this;
    }
}
