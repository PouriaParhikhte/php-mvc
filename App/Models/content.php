<?php

namespace App\Models;

use Core\Helpers\Configs;
use Core\crud\Select;
use Core\Helpers\mysqlClause\InnerJoin;
use Core\Helpers\mysqlClause\Where;
use Core\Helpers\Paging;

class Content extends Select
{
    use InnerJoin, Where, Paging;

    protected $table = 'content';

    public function getContent($url)
    {
        return $this->select()->innerJoin('url', 'urlId')->where(['url', $url])->paging(Configs::perPage());
    }
}
