<?php

namespace Core\Helpers;

use Core\Helper;
use Core\Helpers\Token\Token;

class Csrf
{
    use Prototype;

    public static function verifyToken()
    {
        $request = Http::request();
        $message = '!توکن نامعتبر';
        if (!isset($request->csrf) || $request->csrf !== Helper::token()->getToken()->csrf)
            Helper::response($message)->responseText($message);
        return 1;
    }

    public static function addTokenFieldintoFormElements(&$form)
    {
        Token::$token['csrf'] = md5(SETTINGS->UNIQUEHASH . $_SERVER['REMOTE_ADDR'] . Token::getInstance()->getIssuedTime());
        $pos = strpos($form, '<input');
        $form = substr($form, 0, $pos) . '<input type="hidden" name="csrf" value="' . Token::$token['csrf'] . '">' . substr($form, $pos);
    }
}
