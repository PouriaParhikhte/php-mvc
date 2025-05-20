<?php

namespace Core\Middlewares;

use Core\Helpers\Helper;

class Auth
{
    use Helper;

    public static function isAdmin()
    {
        $token = (new self)->token()->getToken();
        return (isset($token->userId, $token->roleId) && $token->roleId === 1);
    }

    public static function isCustomer()
    {
        $token = (new self)->token()->getToken();
        if (isset($token->userId, $token->roleId) && $token->roleId === 2)
            return (new self)->parentObject();
        (new self)->invalidRequest();
    }
}
