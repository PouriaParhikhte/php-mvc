<?php

namespace App\Models;

use Core\Crud\Select;

class CustomerAccountForm extends Select
{
    protected $table = 'htmlTags';

    public static function fetch()
    {
        return (new self)->select(['elementTitle', 'tags'])->where('elementTitle', 'customerAccountForm')->first();
    }
}
