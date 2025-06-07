<?php

namespace Core\Middlewares;

use Core\Helper;
use Core\Helpers\Http;
use Core\Helpers\Token\Token;
use Core\Validation;
use Exception;

class MobileNumberValidation extends Validation
{
    public function validate()
    {
        try {
            if (isset(Http::request()->mobileNumber)) {
                $this->field('mobileNumber', 'شماره موبایل')->required()->pattern("/^(09){1}[0-9]{9}$/");
                Token::$token = ['iat' => .1, 'temporaryCode' => rand(1000, 9999), 'mobileNumber' => Http::request()->mobileNumber];
                Helper::response('temporaryCode')->redirect();
            }
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'mobileNumber');
        }
    }
}
