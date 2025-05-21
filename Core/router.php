<?php

namespace Core;

use App\Controllers\Customer\CustomerAccountController;
use App\Controllers\Customer\CustomerLogoutController;
use App\Controllers\DatabaseController;
use App\Controllers\MainController;
use Core\Helpers\Csrf;
use Core\Helpers\Form;
use Core\Helpers\Prototype;
use Core\Helpers\Token\Token;
use Core\Middlewares\MobileNumberValidation;
use Core\Middlewares\TemporaryCode;

class router extends Route
{
    use Prototype;

    public function __construct($url)
    {
        Helper::checkRequestTimestamp();
        Token::$token = ['url' => $url, 'userIp' => $_SERVER['REMOTE_ADDR']];

        $urlComponents = explode('/', $url);
        if (count($urlComponents) > 2) {
            $urlComponents = array_slice($urlComponents, 0, -1);
            $url = implode('/', $urlComponents);
        }
        method_exists($this, $urlComponents[0]) ? call_user_func_array([$this, $urlComponents[0]], [$url]) : $this->loadUrl($url);
    }

    private function loadUrl($url)
    {
        match ($url) {
            '404' => Helper::notFound(),
            'create/database' => $this->loadControllerAndAction(DatabaseController::class, 'create'),
            'drop/database' => $this->loadControllerAndAction(DatabaseController::class, 'drop'),
            'cache/clear' => $this->loadControllerAndAction(DatabaseController::class, 'truncate'),
            default =>  $this->loadControllerAndAction(MainController::class, 'index', $url)
        };
    }

    private function customer($url)
    {
        match ($url) {
            'customer/account' => Csrf::verifyToken() ?: Form::getInstance()->__invoke('customer')->checkSingleField('mobileNumber') ?: MobileNumberValidation::getInstance()->validate(),
            'customer/temporaryCode' => Csrf::verifyToken() ?: Form::getInstance()->__invoke('customer')->checkSingleField('temporaryCode') ?: TemporaryCode::getInstance()->verify(),
            'customer/login' => $this->loadControllerAndAction(CustomerAccountController::class, 'check'),
            'customer/logout' => Helper::isCustomer() ?: $this->loadControllerAndAction(CustomerLogoutController::class, 'logout')
        };
    }
}
