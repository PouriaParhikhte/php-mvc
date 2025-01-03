<?php

namespace App\Api\Panel;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Token\CreateToken;
use Exception;

class PanelLogoutController extends Controller
{
    public function logout()
    {
        try {
            CreateToken::getInstance()->create(['userId' => null, 'roleId' => null]);
            throw new Exception;
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
