<?php

namespace App\Controllers;

use Core\Helpers\Prototype;
use App\Models\Tables;
use Core\Helpers\Configs;
use Core\Helpers\Helper;

class DatabaseController
{
    use Prototype;

    public static function create()
    {
        $tables = new Tables;
        $methods = get_class_methods($tables);
        $tables->chooseDatabase(Configs::database());
        $index = array_search('chooseDatabase', $methods);
        array_walk($methods, function ($value, $key) use ($tables, $index) {
            if ($key === $index)
                Helper::redirect();
            call_user_func([$tables, $value]);
        });
    }
}
