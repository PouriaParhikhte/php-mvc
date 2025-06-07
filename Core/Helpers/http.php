<?php

namespace Core\Helpers;

use Core\Helper;
use Core\Validation;

class Http
{
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
        return Helper::toJson($_REQUEST);
    }

    public static function url()
    {
        return Helper::toJson(rtrim($_REQUEST['url'] ?? SETTINGS->HOMEPAGEURL, '/'));
    }
}
