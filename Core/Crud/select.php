<?php

namespace Core\Crud;

use Core\Helpers\mysqlClause\Clause;

class Select extends Clause
{
    public function select(array $columns = ['*'])
    {
        $columns = implode(',', $columns);
        $this->sql->query = "SELECT $columns FROM `$this->table`";
        $this->sql->type = 'select';
        return $this;
    }
}
