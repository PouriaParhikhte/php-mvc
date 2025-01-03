<?php

namespace App\Models\Ticket;

use Core\Crud\Insert;

class Register extends Insert
{
    protected $table = 'ticket';

    public static function ticket(array $formData)
    {
        return (new self)->insert($formData)->getResult();
    }
}
