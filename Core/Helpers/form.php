<?php

namespace Core\Helpers;

use Core\Connection\MysqliConnection;
use Core\Model;
use Exception;

class Form extends Model
{
    use Prototype;

    public function checkFormFields(array $fields, $guards = [])
    {
        $columns = $this->getTableColumnsName($guards);
        $keys = array_keys($fields);
        if (array_diff($columns, $keys))
            throw new Exception('فیلد(های) فرم نامعتبر میباشد!');
        return new self;
    }

    public function getTableColumnsName($guards = []): array
    {
        $sql = "SHOW COLUMNS FROM $this->table";
        $fields = MysqliConnection::create()->query($sql)->fetch_all(1);
        $fields = array_column($fields, 'Field');
        if (!empty($guards))
            $fields = array_diff($fields, $guards);
        return array_values($fields);
    }
}
