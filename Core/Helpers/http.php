<?php

namespace Core\Helpers;

use Core\Validation;

class Http
{
    use Helper;

    public static function requestHeaders()
    {
        return json_decode(json_encode(getallheaders()));
    }

    public function validation()
    {
        return new Validation;
    }

    public static function request()
    {
        return (new self)->toJson($_REQUEST);
    }

    public static function url()
    {
        return (new self)->toJson($_REQUEST['url'] ?? SETTINGS->HOMEPAGEURL);
    }
}
