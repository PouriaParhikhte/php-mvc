<?php

namespace Core\Helpers\Token;

use Core\Helpers\Prototype;
use Core\Helpers\TokenDecoder;

class GetDecodedToken
{
    use Prototype, TokenDecoder;

    public function fetch(int $toArray = 0)
    {
        return GetPreviousToken::getInstance()->fetch($toArray);
    }
}
