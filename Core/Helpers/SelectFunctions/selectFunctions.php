<?php

namespace Core\Helpers\SelectFunctions;

use Core\Model;

class SelectFunctions extends Model
{
    public function selectAvg($column)
    {
        $this->sql->query = "SELECT AVG($column) FROM `$this->table`";
        $this->sql->type = 'selectAvg';
        return $this;
    }

    public function selectCount($column)
    {
        $this->sql->query = "SELECT COUNT($column) FROM `$this->table`";
        $this->sql->type = 'selectCount';
        return $this;
    }

    public function selectMax($column)
    {
        $this->sql->query = "SELECT MAX($column) FROM `$this->table`";
        $this->sql->type = 'selectMax';
        return $this;
    }

    public function selectMin($column)
    {
        $this->sql->query = "SELECT MIN($column) FROM `$this->table`";
        $this->sql->type = 'selectMin';
        return $this;
    }

    public function selectSum($column)
    {
        $this->sql->query = "SELECT SUM($column) FROM `$this->table`";
        $this->sql->type = 'selectSum';
        return $this;
    }
}
