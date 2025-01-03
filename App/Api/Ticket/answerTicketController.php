<?php

namespace App\Api\Ticket;

use App\Models\Ticket\AnswerTicket;
use Core\Controller;
use Core\Helpers\Helper;
use Core\Validation;
use Exception;

class AnswerTicketController extends Controller
{
    public function create(Validation $validation)
    {
        try {
            $this->params['answer'] = trim($this->params['answer']);
            $validation->field('answer', 'متن پاسخ')->required();
            unset($this->params['token']);
            $message = !AnswerTicket::register($this->params) ? 'ثبت پاسخ با خطا همراه شد' : 'پاسخ با موفقیت ثبت شد';
            throw new Exception($message);
        } catch (Exception $exception) {
            Helper::sendMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}
