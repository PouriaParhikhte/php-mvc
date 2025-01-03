<?php

namespace Core\Helpers\mysqlClause;

trait EnableForeignKeyCheck
{
    protected $sql;

    public function enableForeignKeyCheck()
    {
        $this->sql = "SET FOREIGN_KEY_CHECKS=1;";
        return $this;
    }
}
