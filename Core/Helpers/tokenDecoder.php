<?php

namespace Core\Helpers;

trait TokenDecoder
{
    public function decodeToken($token, int $toArray = 0)
    {
        $token = explode('.', $token);
        $token = base64_decode($token[1]);
        return json_decode($token, $toArray);
    }
}
