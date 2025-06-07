<?php

namespace Core;

use App\Controllers\Customer\CustomerAccountController;
use App\Controllers\Customer\CustomerLogoutController;
use App\Controllers\DatabaseController;
use App\Controllers\Image\ImageController;
use App\Controllers\MainController;
use Core\Helpers\Form;
use Core\Helpers\Http;
use Core\Helpers\Token\Token;
use Core\Middlewares\Auth;
use Core\Middlewares\ImageUploader;
use Core\Middlewares\MobileNumberValidation;
use Core\Middlewares\TemporaryCode;

class Router extends Route
{
    public function __construct()
    {
        Token::$token = ['url' => Http::url(), 'userIp' => $_SERVER['REMOTE_ADDR']];
        method_exists($this, PARAMS[0]) ? call_user_func_array([$this, PARAMS[0]], [Http::url()]) : $this->loadUrl(Http::url());
    }

    private function loadUrl($url)
    {
        match ($url) {
            '404' => Helper::notFound(),
            'create/database' => $this->loadControllerAndAction(DatabaseController::class, 'create'),
            'drop/database' => $this->loadControllerAndAction(DatabaseController::class, 'drop'),
            'cache/clear' => $this->loadControllerAndAction(DatabaseController::class, 'truncate'),
            'upload' => ImageUploader::getInstance()->imageTypes(['image/jpg', 'image/jpeg', 'image/png'])->imageSize(50000) ?: $this->loadControllerAndAction(ImageController::class, 'uploadPostImage'),
            default =>  $this->loadControllerAndAction(MainController::class, 'index', $url)
        };
    }

    private function customer($url)
    {
        Helper::postRequestMethod()?->csrf()->verifyToken() ? $this->customerPostRequests($url) : $this->customerGetRequests($url);
    }

    private function customerGetRequests($url)
    {
        match ($url) {
            'customer/login' => $this->loadControllerAndAction(CustomerAccountController::class, 'check'),
            'customer/logout' => Auth::isCustomer() ?: $this->loadControllerAndAction(CustomerLogoutController::class, 'logout'),
            default => Helper::notFound()
        };
    }

    private function customerPostRequests($url)
    {
        match ($url) {
            'customer/account' => Form::getInstance()->__invoke('customer')->checkSingleField('mobileNumber') ?: MobileNumberValidation::getInstance()->validate(),
            'customer/temporaryCode' => Form::getInstance()->__invoke('customer')->checkSingleField('temporaryCode') ?: TemporaryCode::getInstance()->verify(),
        };
    }
}
