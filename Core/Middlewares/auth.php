<?php

namespace Core\Middlewares;

use Core\Helper;

class Auth
{
    private $token;

    public function __construct()
    {
        $this->token = Helper::token()->getToken();
    }

    public static function isAdmin()
    {
        return (isset((new self)->token->userId,) || (isset((new self)->token->roleId) && (new self)->token->roleId === 1)) ?: Helper::invalidRequest();
    }

    public static function isCustomer()
    {
        return (!isset((new self)->token->userId,) || (isset((new self)->token->roleId) && (new self)->token->roleId !== 2)) ? Helper::invalidRequest() : null;
    }
}
