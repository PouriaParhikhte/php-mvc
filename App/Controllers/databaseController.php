<?php

namespace App\Controllers;

use Core\Helpers\Prototype;
use App\Models\Tables;

class DatabaseController
{
    use Prototype;

    public static function create()
    {
        $tables = Tables::getInstance();
        $methods = get_class_methods($tables);
        $index = array_search('__construct', $methods);
        $methods = array_splice($methods, 0, $index);
        $tables->chooseDatabase(SETTINGS->DATABASE);
        foreach ($methods as $method) {
            $tables->$method();
        }
    }

    public function drop()
    {
        $sql = "DROP DATABASE IF EXISTS " . SETTINGS->DATABASE;
        if ($this->getConnection()->query($sql))
            exit('Dropped!');
    }

    public function truncate()
    {
        $sql = "TRUNCATE TABLE cache";
        if ($this->getConnection()->query($sql))
            $this->redirectTo(SETTINGS->HOMEPAGEURL);
    }
}
