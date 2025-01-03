<?php

namespace Core\Crud;

use Core\Model;

class SelectCount extends Model
{
    public function selectCount($column)
    {
        $this->sql = "SELECT COUNT($column) FROM `$this->table`";
        return $this;
    }
}
