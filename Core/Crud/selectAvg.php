<?php

namespace Core\Crud;

use Core\Model;

class SelectAvg extends Model
{
    public function selectAvg($column)
    {
        $this->sql = "SELECT AVG($column) FROM `$this->table`";
        return $this;
    }
}
