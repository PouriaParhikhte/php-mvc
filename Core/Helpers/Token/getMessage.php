<?php

namespace Core\Helpers\Token;

use Core\Helpers\Prototype;
use Core\Helpers\TokenDecoder;

class GetMessage
{
    use Prototype, TokenDecoder;

    public function showMessage(string $index)
    {
        $token = GetPreviousToken::getInstance()->fetch(1);
        echo $token[$index] ?? null;
        unset($token[$index]);
        CreateToken::getInstance()->create($token);
    }
}
