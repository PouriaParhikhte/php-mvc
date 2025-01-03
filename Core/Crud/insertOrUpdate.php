<?php

namespace Core\Crud;

class InsertOrUpdate extends Insert
{
    public function insertOrUpdate(array $input)
    {
        if (!$this->insert($input)->getResult()) {
            $update = new Update;
            $update->__invoke($this->table)->update($input)->getResult();
        }
        return $this;
    }
}
