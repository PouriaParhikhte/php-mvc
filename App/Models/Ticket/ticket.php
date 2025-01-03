<?php

namespace App\Models\Ticket;

use Core\Crud\Select;
use Core\Helpers\mysqlClause\Limit;
use Core\Helpers\mysqlClause\Where;

class Ticket extends Select
{
    protected $table = 'ticket';
    use Where, Limit;

    public static function fetch($ticketId)
    {
        return (new self)->select()->where(['ticketId', $ticketId])->first();
    }
}
