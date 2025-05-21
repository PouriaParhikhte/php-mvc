<?php

namespace Core\Middlewares;

use Core\Helper;

class Auth
{
    public static function isAdmin()
    {
        $token = Helper::token()->getToken();
        return (isset($token->userId, $token->roleId) && $token->roleId === 1);
    }

    public static function isCustomer()
    {
        $token = Helper::token()->getToken();
        if (isset($token->userId, $token->roleId) && $token->roleId === 2)
            return Helper::parentObject();
        Helper::invalidRequest();
    }
}
