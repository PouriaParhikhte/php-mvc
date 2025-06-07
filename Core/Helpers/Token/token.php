<?php

namespace Core\Helpers\Token;

use Core\Helper;
use Core\Helpers\Prototype;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token
{
    public static $token;
    use Prototype;

    public function generate(array $input = [])
    {
        self::$token = $input;
        $this->payload(self::$token);
        return $this->mergePayloadWithInputArray($input, self::$token)->mergePayloadWithPreviousToken(self::$token)->generateJwtAndStoreItInCookie(self::$token);
    }

    private function payload(&$output)
    {
        $timestamp = microtime(true);
        $payload = ['iss' => SETTINGS->DOMAIN, 'iat' => $timestamp, 'nbf' => $timestamp];
        $output = array_merge(self::$token, $payload);
        return $this;
    }

    private function mergePayloadWithInputArray($input, &$payload)
    {
        if (!empty($input))
            $payload = array_merge($payload, $input);
        return $this;
    }

    public function getIssuedTime()
    {
        return $this->getToken()->iat ?? null;
    }

    public function getToken(int $toArray = 0)
    {
        if (isset($_COOKIE['token'])) {
            $token = $this->decodeToken();
            if ($toArray)
                return json_decode(json_encode($token), 1);
            return $token;
        }
    }

    private function mergePayloadWithPreviousToken(&$payload)
    {
        if ($previousToken = $this->getToken()) {
            $this->removeAditionalPropertiesFromToken($previousToken);
            $payload = array_merge((array)$previousToken, $payload);
        }
        return $this;
    }

    private function removeAditionalPropertiesFromToken(&$previousToken)
    {
        $properties = ['message', 'responseCode'];
        foreach ($properties as $property) {
            if (property_exists($previousToken, $property))
                unset($previousToken->$property);
        }
    }

    private function generateJwtAndStoreItInCookie($payload)
    {
        $payload = array_filter($payload);
        $token = JWT::encode($payload, $this->secretKey(), 'HS256');
        setcookie('token', $token, time() + 300, '/');
        return Helper::getInstance();
    }

    private function secretKey()
    {
        return md5($_SERVER['REMOTE_ADDR'] . SETTINGS->UNIQUEHASH . $_SERVER['HTTP_USER_AGENT']);
    }

    private function decodeToken()
    {
        if (array_key_exists('token', $_COOKIE))
            return JWT::decode($_COOKIE['token'], new Key($this->secretKey(), 'HS256'));
    }

    public function isLogged()
    {
        $token = $this->getToken();
        return isset($token->userId, $token->roleId);
    }

    public function checkRequestTimestamp()
    {
        if ($token = $this->getToken())
            if ($token->userIp === $_SERVER['REMOTE_ADDR'] && !isset($token->responseCode) && (microtime(true) - $token->iat) < .2)
                Helper::tooManyRequests();
        return $this;
    }
}
