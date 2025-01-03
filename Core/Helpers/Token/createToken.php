<?php

namespace Core\Helpers\Token;

use Core\Crud\InsertOrUpdate;
use Core\Helpers\Configs;
use Core\Helpers\TokenDecoder;
use Firebase\JWT\JWT;

class CreateToken extends InsertOrUpdate
{
    protected $table = 'session';
    use TokenDecoder;

    public function __construct(array $input = [])
    {
        if ($input !== []) {
            $token = GetPreviousToken::getInstance()->fetch(1);
            $input = array_merge($input, $token);
            $this->create($input);
        }
    }

    public function create(array $input = [])
    {
        $timestamp = microtime(true);
        $this->payload($timestamp, $payload);

        $payload = array_merge($payload, $input);

        $arr['userIp'] = $_SERVER['REMOTE_ADDR'];
        $arr['token'] = JWT::encode($payload, md5($timestamp), 'HS256');
        $this->insertOrUpdate($arr);
    }

    private function payload($timestamp, &$payload)
    {
        $payload = [
            'iss' => Configs::domain(),
            'iat' => $timestamp,
            'nbf' => $timestamp,
            'exp' => $timestamp + Configs::jwtExpireSeconds()
        ];
        return $this;
    }
}
