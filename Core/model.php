<?php

namespace Core;

use Core\connection\MysqliConnection;
use Core\Helpers\Json;
use Core\Helpers\Prototype;
use Exception;

abstract class Model
{
    use Prototype, Json;
    private $result;
    protected $table, $values, $sql;

    public function __invoke($table)
    {
        $this->table = $table;
        return $this;
    }

    protected function query(string $sql, array $values = null): mixed
    {
        try {
            // var_dump($sql);
            // var_dump($values);
            $stmt = MysqliConnection::create()->prepare($sql);
            if (!empty($values))
                $this->getValueType($values, $stmt);
            $this->executeStatement($stmt);
            return $this->result;
        } catch (Exception) {
            return $this->result;
        }
    }

    private function executeStatement($stmt): void
    {
        if (!$stmt->execute())
            throw new Exception($stmt->error);
        $this->result = $stmt->get_result() ?: mysqli_stmt_affected_rows($stmt);
        $stmt->close();
    }

    private function getValueType(array $values, $stmt): void
    {
        $types = array_map([$this, 'validation'], $values);
        $types = implode('', $types);
        array_unshift($values, $types);
        $this->getValuesAsRefrence($values, $values)->bindParameters($stmt, $values);
    }

    private function validation($value): string
    {
        if (!$value)
            return 's';
        $filters = ['integer' => 'int', 'double' => 'float'];
        if (is_array($value))
            $value = $value[0];
        $valueType = gettype($value);
        $valueType = array_key_exists($valueType, $filters) ? $filters[$valueType] : $valueType;
        $filterId = filter_id($valueType);
        if (filter_var($value, $filterId) === false)
            throw new Exception('Invalid format!');
        return $valueType[0];
    }

    private function getValuesAsRefrence(array $values, &$output = [])
    {
        foreach ($values as $key => $val) {
            $output[$key] = &$values[$key];
        }
        return $this;
    }

    private function bindParameters($stmt, array $values): void
    {
        call_user_func_array([$stmt, 'bind_param'], $values);
    }

    protected function getResult(int $toArray = null): mixed
    {
        $this->query($this->sql, $this->values);
        if (!$this->result)
            return 0;
        $this->values = [];
        return is_object($this->result) ? $this->toJson($this->result->fetch_all(1), $toArray) : $this->result;
    }

    protected function getInsertId(): mixed
    {
        $this->query($this->sql, $this->values);
        $this->values = [];
        return MysqliConnection::create()->insert_id;
    }

    protected function getNumRows(): int
    {
        return $this->query($this->sql, $this->values)->num_rows ?? 0;
    }
}
