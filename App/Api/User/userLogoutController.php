<?php

namespace App\Api\User;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Token\CreateToken;
use Exception;

class UserLogoutController extends Controller
{
    public function logout()
    {
        try {
            CreateToken::getInstance()->create(['userId' => null, 'roleId' => null]);
            throw new Exception;
        } catch (Exception $exception) {
            Helper::redirectTo(null, $exception->getMessage(), $exception->getCode());
        }
    }
}
