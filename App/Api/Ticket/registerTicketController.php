<?php

namespace App\Api\Ticket;

use App\Models\Ticket\Register;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Helpers\Token\GetDecodedToken;
use Core\Validation;
use Exception;

class RegisterTicketController extends Controller
{
    public function create(Validation $validation)
    {
        try {
            $validation->allRequired($this->params);
            unset($this->params['token']);
            $message = !Register::ticket($this->params) ? 'ثبت تیکت با خطا همراه شد' : 'تیکت با موفقیت ثبت شد';
            throw new Exception($message);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
