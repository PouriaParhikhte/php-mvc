<?php

namespace Core\Crud;

use Core\Model;

class SelectMax extends Model
{
    public function selectMax($column)
    {
        $this->sql = "SELECT MAX($column) FROM `$this->table`";
        return $this;
    }
}
