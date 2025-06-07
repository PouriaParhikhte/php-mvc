<?php

namespace Core\Crud;

class InsertOrUpdate extends Insert
{
    public function insertOrUpdate(array $input)
    {
        if (!$this->insert($input)->getResult())
            Update::getInstance()->__invoke($this->table)->update($input);
        return $this;
    }
}
