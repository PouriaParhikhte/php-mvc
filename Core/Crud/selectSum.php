<?php

namespace Core\Crud;

use Core\Model;

class SelectSum extends Model
{
    public function selectSum($column)
    {
        $this->sql = "SELECT SUM($column) FROM `$this->table`";
        return $this;
    }
}
