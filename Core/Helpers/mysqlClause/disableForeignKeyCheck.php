<?php

namespace Core\Helpers\mysqlClause;

trait DisableForeignKeyCheck
{
    protected $sql;

    public function disableForeignKeyCheck()
    {
        $this->sql = "SET FOREIGN_KEY_CHECKS=0;";
        return $this;
    }
}
