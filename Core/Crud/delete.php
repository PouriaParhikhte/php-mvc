<?php

namespace Core\Crud;

use Core\Model;

class Delete extends Model
{
    protected function delete()
    {
        $this->sql = "DELETE FROM `$this->table`";
        return $this;
    }
}
