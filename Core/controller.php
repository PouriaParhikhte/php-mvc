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
        $this->params = $this->toJson(explode('/', $input));
    }

    public function __destruct()
    {
        $this->token()->createToken(Token::$token);
    }
}
