<?php

namespace App\Api\Panel;

use Core\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Token;
use Core\Helpers\Token\fetchValueFromToken;
use Core\View;
use Exception;

class ManagerController extends Controller
{
    public function index()
    {
        try {
            $page = (fetchValueFromToken::getInstance()->fetch('userId') !== 0 && fetchValueFromToken::getInstance()->fetch('roleId') === 1) ? 'api/panel/dashboard' : 'api/panel/manager';
            View::render($page);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
