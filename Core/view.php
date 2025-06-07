<?php

namespace Core;

define('VIEWS', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR);
define('HEADER', VIEWS . 'Layout' . DIRECTORY_SEPARATOR . 'header.php');
define('FOOTER', VIEWS . 'Layout' . DIRECTORY_SEPARATOR . 'footer.php');

class View
{
    public static function render(string $page, $input = null)
    {
        if (!file_exists(VIEWS . "$page.php"))
            Helper::notFound();
        if ($input !== [])
            extract((array)$input, EXTR_SKIP);
        include VIEWS . "$page.php";
        exit;
    }
}
