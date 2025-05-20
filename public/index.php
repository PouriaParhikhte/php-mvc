<?php

declare(strict_types=1);

use Core\Helpers\Http;
use Core\Helpers\Token\Token;
use Core\router;

include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
$env = parse_ini_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');
$env = json_encode($env);

define('SETTINGS', json_decode($env));

Token::getInstance()->checkRequestTimestamp();
router::getInstance(rtrim(Http::url(), '/'));
