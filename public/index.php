<?php

declare(strict_types=1);

use Core\Helpers\Configs;
use Core\Router;

include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
new Router($_GET['url'] ?? Configs::homePageUrl());
