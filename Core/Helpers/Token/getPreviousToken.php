<?php

namespace Core\Helpers\Token;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Where;
use Core\Helpers\TokenDecoder;

class GetPreviousToken extends Select
{
    protected $table = 'session';
    use Where, Limit, TokenDecoder;

    public function fetch(int $toArray = 0)
    {
        $token = $this->select()->where(['userip', $_SERVER['REMOTE_ADDR']])->first()->token ?? null;
        return $this->decodeToken($token, $toArray);
    }
}
