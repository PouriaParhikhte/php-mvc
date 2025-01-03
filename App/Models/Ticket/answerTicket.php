<?php

namespace App\Models\Ticket;

use Core\Crud\Update;

class AnswerTicket extends Update
{
    protected $table = 'ticket';

    public static function register(array $formData)
    {
        if ((new self)->update($formData)->where(['ticketId', $formData['ticketId']])->getResult()) {
            (new self)->update(['status' => '1'])->where(['ticketId', $formData['ticketId']])->getResult();
            return 1;
        }
    }
}
