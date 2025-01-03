<?php

namespace Core\Crud;

use Core\Helpers\mysqlClause\Where;
use Core\Model;

class Update extends Model
{
    use Where;

    public function update(array $input)
    {
        $this->sql = "UPDATE `$this->table` SET";
        $this->updateQuery($input, $this->sql);
        $this->values = array_values($input);
        return $this;
    }

    private function updateQuery(array $input, $sql)
    {
        $position = strpos($this->sql, 'ON DUPLICATE KEY UPDATE');
        if ($position !== false) {
            $columns = $this->getTableColumnsName();
            if (in_array('timestamp', $columns))
                $input['timestamp'] = 'timestamp';
        }
        array_walk($input, function ($value, $key) use ($position) {
            $this->sql .= ($position) ? " $key = VALUES($key)," : " $key = ?,";
        });
        $this->sql = rtrim($this->sql, ',');
        return $this;
    }
}
