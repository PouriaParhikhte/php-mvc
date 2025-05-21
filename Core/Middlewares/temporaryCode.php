<?php

namespace Core\Middlewares;

use Core\Helper;
use Core\Helpers\Http;
use Core\Helpers\Prototype;
use Exception;

class TemporaryCode
{
    use Prototype;

    public function verify()
    {
        try {
            $token = Helper::token()->getToken();
            if (!isset($token->mobileNumber, $token->temporaryCode) || Http::request()->temporaryCode != $token->temporaryCode)
                throw new Exception('کد یکبار مصرف نامعتبر میباشد', 302);
            Helper::redirectTo('customer/login');
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
