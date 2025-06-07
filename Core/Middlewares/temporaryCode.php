<?php

namespace Core\Middlewares;

use Core\Helper;
use Core\Helpers\Http;
use Core\Helpers\Prototype;
use Core\Helpers\Token\Token;
use Exception;

class TemporaryCode
{
    use Prototype;

    public function verify()
    {
        try {
            Token::$token = ['iat' => .1];
            $token = Helper::token()->getToken();
            if (!isset($token->mobileNumber, $token->temporaryCode) || Http::request()->temporaryCode != $token->temporaryCode)
                throw new Exception('کد یکبار مصرف نامعتبر میباشد', 302);
            Helper::redirectTo('customer/login');
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode(), 'temporaryCode');
        }
    }
}
