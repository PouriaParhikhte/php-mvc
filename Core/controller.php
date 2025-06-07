<?php

namespace Core;

use Core\Helpers\Prototype;
use Core\Helpers\Token\Token;

abstract class Controller
{
    use Prototype;

    public function __destruct()
    {
        Helper::token()->generate(Token::$token);
    }
}
