<?php

namespace Core\Helpers;

use Core\Helper;
use Exception;

class Form
{
    use Prototype;
    private $table;

    public function __invoke($table)
    {
        $this->table = $table;
        return $this;
    }

    public function checkFormFields(array $fields, $guards = [])
    {
        try {
            $columns = $this->getTableColumnsName($guards);
            $keys = array_keys($fields);
            if (array_diff($columns, $keys))
                throw new Exception('فیلد(های) فرم نامعتبر میباشد!', 302);
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }

    public function getTableColumnsName($guards = []): array
    {
        $sql = "SHOW COLUMNS FROM $this->table";
        $fields = Helper::getConnection()->query($sql)->fetch_all(1);
        $fields = array_column($fields, 'Field');
        if (!empty($guards))
            $fields = array_diff($fields, $guards);
        return array_values($fields);
    }

    public function checkSingleField(string $fieldName)
    {
        try {
            if (!property_exists(Http::request(), $fieldName))
                throw new Exception('فیلد(های) فرم نامعتبر میباشد!', 302);
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
