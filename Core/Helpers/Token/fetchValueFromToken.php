<?php

namespace Core\Helpers\Token;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Where;

class fetchValueFromToken extends Select
{
    use Where, Limit;

    public function fetch(string $index)
    {
        return GetDecodedToken::getInstance()->fetch()->$index ?? null;
    }
}
