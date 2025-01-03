<?php

namespace Core\Helpers;

trait Json
{
    public function toJson($input, int $toArray = null): mixed
    {
        $input = json_encode($input);
        return json_decode($input, $toArray);
    }
}
