<?php

namespace Core\Crud;

use Core\Model;

class Delete extends Model
{
    protected function delete()
    {
        $this->sql->query = "DELETE FROM `$this->table`";
        $this->sql->type = 'delete';
        return $this;
    }
}
