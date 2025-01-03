<?php

namespace App\Models\Ticket;

use Core\Helpers\Configs;
use Core\Crud\Select;
use Core\Helpers\Paging;

class Received extends Select
{
    use Paging;
    protected $table = 'ticket';

    public static function tickets()
    {
        return (new self)->select()->paging(Configs::perPage());
    }
}
