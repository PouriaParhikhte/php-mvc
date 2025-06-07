<?php

namespace Core\Connection;

use App\Controllers\DatabaseController;
use Core\Helper;
use Core\Helpers\Database\DatabaseManagementSystem;
use Core\Helpers\Prototype;
use Exception;
use mysqli;
use stdClass;

class MysqliConnection
{
    use Prototype;
    protected static $connection;
    protected $table, $sql, $values;

    public function __construct()
    {
        $this->sql = new stdClass;
        if (!isset(self::$connection))
            self::$connection = self::create();
    }

    public function __invoke($table)
    {
        $this->table = $table;
        return $this;
    }

    private function executeQuery()
    {
        try {
            if (!empty($this->values)) {
                foreach ($this->values as $value) {
                    $this->sql->query = str_replace("'$value'", '?', $this->sql->query);
                }

                if ($result = self::$connection->execute_query($this->sql->query, $this->values))
                    if (isset($this->sql->type) && $this->sql->type === 'select') {
                        $this->values = [];
                        return Helper::toJson($result->fetch_all(1));
                    }
                return $result;
            }
        } catch (Exception) {
            DatabaseController::create();
        }
    }

    public function fetchResult()
    {
        $result = self::$connection->query($this->sql->query);
        return Helper::toJson($result->fetch_all(1));
    }

    public function result()
    {
        return self::$connection->query($this->sql->query);
    }

    public function getResult()
    {
        return $this->executeQuery();
    }

    public function getNumRows(): int
    {
        return self::$connection->query($this->sql->query)->num_rows;
    }

    public static function create()
    {
        try {
            return self::$connection ?: self::mysqliObject();
        } catch (Exception) {
            self::$connection = self::mysqliObjectWithoutDatabase();
            DatabaseManagementSystem::getInstance()->createDatabase(SETTINGS->DATABASE)->result();
            Helper::token()->generate(['iat' => .1])->redirect();
        }
    }

    public static function mysqliObject()
    {
        self::$connection = new mysqli(SETTINGS->HOST, SETTINGS->USERNAME, SETTINGS->PASSWORD, SETTINGS->DATABASE);
        self::$connection->set_charset(SETTINGS->CHARSET);
        return self::$connection;
    }

    public static function mysqliObjectWithoutDatabase()
    {
        self::$connection = new mysqli(SETTINGS->HOST, SETTINGS->USERNAME, SETTINGS->PASSWORD);
        self::$connection->set_charset(SETTINGS->CHARSET);
        return self::$connection;
    }

    private static function getValueType(array $values, $stmt)
    {
        $types = array_map([self::class, 'validation'], $values);
        $types = implode('', $types);
        array_unshift($values, $types);
        self::getValuesAsRefrence($values, $values)
            ->bindParameters($stmt, $values);
        return new static;
    }

    private static function validation($value): string
    {
        $filters = ['integer' => 'int', 'double' => 'float'];
        if (is_array($value))
            $value = $value[0];
        $valueType = gettype($value);
        $valueType = array_key_exists($valueType, $filters)
            ? $filters[$valueType] : $valueType;
        $filterId = filter_id($valueType);
        if (filter_var($value, $filterId) === false)
            throw new Exception('Invalid format!');
        return $valueType[0];
    }

    private static function getValuesAsRefrence(array $values, &$output = [])
    {
        foreach ($values as $key => $val) {
            $output[$key] = &$values[$key];
        }
        return new static;
    }

    private static function bindParameters($stmt, array $values): void
    {
        call_user_func_array([$stmt, 'bind_param'], $values);
    }
}
