<?php

namespace App\Models\User;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Where;

class CheckIfUsernameExists extends Select
{
    use Where;
    protected $table = 'user';

    public static function check($username)
    {
        return (new self)->select(['username'])->where(['username', $username])->getNumRows();
    }
}
