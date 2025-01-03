<?php

namespace Core;

use Core\Helpers\Prototype;

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

abstract class Controller
{
    protected $params;
    use Prototype;

    public function __construct(mixed $input = null)
    {
        $this->params = $input;
    }

    public function __destruct()
    {
        // Helper::log(['message' => Token::fetchValueFromToken('message')]);
    }
}
