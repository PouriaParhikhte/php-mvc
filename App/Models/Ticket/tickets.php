<?php

namespace App\Models\Ticket;

use Core\Helpers\Configs;
use Core\Crud\Select;
use Core\Helpers\mysqlClause\OrderBy;
use Core\Helpers\mysqlClause\Where;
use Core\Helpers\Paging;

class Tickets extends Select
{
    protected $table = 'ticket';
    use Where, OrderBy, Paging;

    public static function index($userId)
    {
        return (new self)->select()->where(['userId', $userId])->orderBy('ticketId', 'DESC')->paging(Configs::perPage());
    }
}
