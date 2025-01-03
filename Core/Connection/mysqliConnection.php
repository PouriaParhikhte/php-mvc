<?php

namespace Core\Connection;

use App\Controllers\DatabaseController;
use App\Models\Tables;
use Core\Helpers\Configs;
use Exception;
use mysqli;

class MysqliConnection
{
    private static $connection;

    public static function create(): mixed
    {
        try {
            if (!isset(self::$connection)) {
                self::$connection = new mysqli(Configs::hostName(), Configs::username(), Configs::password(), Configs::database());
                self::$connection->set_charset(Configs::charset());
            }
            return self::$connection;
        } catch (Exception $exception) {
            $database = Configs::database();
            if ($exception->getMessage() === "Unknown database '$database'")
                DatabaseController::create(new Tables);
            exit($exception->getMessage());
        }
    }

    public static function getError(): string
    {
        return self::$connection->error;
    }
}
