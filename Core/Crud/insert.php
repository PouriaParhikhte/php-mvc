<?php

namespace Core\Crud;

use Core\Helpers\Helper;
use Core\Model;

class Insert extends Model
{
    public function insert(array $input)
    {
        Helper::getArrayKeysAsString($input, $columns)->getArrayValuesAsString($input, $values);
        $this->sql = "INSERT INTO `$this->table` ($columns) VALUES ($values)";
        $this->values = array_values($input);
        return $this;
    }
}
