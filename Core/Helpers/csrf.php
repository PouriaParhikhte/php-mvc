<?php

namespace Core\Helpers;

use Core\Helpers\Token\Token;

class Csrf
{
    use Prototype;

    public static function generate()
    {
        $token = md5(SETTINGS->UNIQUEHASH . $_SERVER['REMOTE_ADDR'] . Token::getInstance()->getIssuedTime());
        Token::$token['csrf'] = $token;
        return $token;
    }

    public static function verifyToken()
    {
        $request = Http::request();
        $message = 'Invalid token!';
        if (!isset($request->csrf) || $request->csrf != (new self)->token()->getToken()->csrf) (new self)->response($message)->responseText($message);
    }

    public static function addTokenFieldintoFormElement(&$form)
    {
        $pos = strpos($form, '<input');
        $form = substr($form, 0, $pos) . '<input type="hidden" name="csrf" value="' . Csrf::generate() . '">' . substr($form, $pos);
    }
}
