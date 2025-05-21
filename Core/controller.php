<?php

namespace Core;

use Core\Helpers\Prototype;
use Core\Helpers\Token\Token;

abstract class Controller
{
    use Prototype;
    protected $params;

    public function __construct(mixed $input = '')
    {
        $this->params = Helper::toJson(explode('/', $input));
    }

    public function __destruct()
    {
        Helper::token()->generate(Token::$token);
    }
}
