<?php

namespace Core\Crud;

use Core\Helpers\Form;
use Core\Helpers\mysqlClause\Clause;

class Update extends Clause
{
    public function update(array $input)
    {
        $this->sql->query = "UPDATE `$this->table` SET";
        $this->sql->type = 'update';
        $this->updateQuery($input, $this->sql->query);
        $this->values = array_values($input);
        return $this;
    }

    private function updateQuery(array $input)
    {
        $position = strpos($this->sql->query, 'ON DUPLICATE KEY UPDATE');
        if ($position !== false) {
            $columns = Form::getInstance()->getTableColumnsName();
            if (in_array('timestamp', $columns))
                $input['timestamp'] = 'timestamp';
        }
        array_walk($input, function ($value, $key) use ($position) {
            $this->sql->query .= ($position) ?
                " $key = VALUES($key)," : " $key = ?,";
        });
        $this->sql->query = rtrim($this->sql->query, ',');
        return $this;
    }
}
