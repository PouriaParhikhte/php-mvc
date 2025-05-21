<?php

namespace Core\Crud;

use Core\Helper;
use Core\Model;

class Insert extends Model
{
    public function insert(array $input)
    {
        Helper::getArrayKeysAsString($input, $columns)->generateStringOfValuesForInsertQuery($input, $values);
        $this->sql->query = "INSERT INTO `$this->table` ($columns) VALUES ($values)";
        $this->sql->type = 'insert';
        $this->values = array_values($input);
        return $this;
    }
}
