<?php

namespace Core\Middlewares;

use Core\Helpers\Http;
use Core\Helpers\Prototype;
use Exception;

class TemporaryCode
{
    use Prototype;

    public function verify()
    {
        try {
            $token = $this->token()->getToken();
            if (!isset($token->mobileNumber, $token->temporaryCode) || Http::request()->temporaryCode != $token->temporaryCode)
                throw new Exception('کد یکبار مصرف نامعتبر میباشد', 302);
            $this->redirectTo('customer/login');
        } catch (Exception $exception) {
            $this->showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
