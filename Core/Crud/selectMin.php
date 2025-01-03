<?php

namespace Core\Crud;

use Core\Model;

class SelectMin extends Model
{
    public function selectMin($column)
    {
        $this->sql = "SELECT MIN($column) FROM `$this->table`";
        return $this;
    }
}
