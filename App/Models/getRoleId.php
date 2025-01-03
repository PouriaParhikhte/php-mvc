<?php

namespace App\Models;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Where;
use Core\Helpers\Token;
use Core\Helpers\Token\fetchValueFromToken;

class GetRoleId extends Select
{
    use Where, Limit;

    protected $table = 'user';

    public function getRoleId()
    {
        $userId = fetchValueFromToken::getInstance()->fetch('userId');
        return $this->select()->where(['userId', $userId])->first();
    }
}
