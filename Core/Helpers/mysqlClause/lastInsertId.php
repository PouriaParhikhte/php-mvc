<?php

namespace Core\Helpers\mysqlClause;

trait LastInsertId
{
    protected $sql, $table;

    public function lastInsertId(): mixed
    {
        $this->sql = "SELECT LAST_INSERT_ID() FROM `$this->table`";
        return $this->getResult();
    }
}
