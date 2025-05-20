<?php

namespace Core\Helpers;

use Core\Connection\MysqliConnection;
use Core\Helpers\Token\Token;
use Core\Middlewares\Auth;
use Core\View;

trait Helper
{
    public function notFound()
    {
        $requestHeaders = Http::requestHeaders();
        if (isset($requestHeaders->type) && $requestHeaders->type === 'xhr')
            exit(json_encode(['redirect' => 1, 'url' => '404']));
        $this->render('404');
    }

    public function setResponseCode(int $responseCode)
    {
        header("$_SERVER[SERVER_PROTOCOL] $responseCode", true, $responseCode);
        http_response_code($responseCode);
        return $this;
    }

    public function setCache(int $seconds)
    {
        header("Cache-Control: max-age=$seconds");
    }

    public function noCache()
    {
        header("Pragma: no-cache");
    }

    public function arrayToString(array $input, $separator = '')
    {
        if (isset($input[0]) && is_array($input[0]))
            foreach ($input as $array) {
                return implode($separator, $array);
            }
        else
            return implode($separator, $input);
    }

    public function redirect(int $responseCode = 0): never
    {
        $url = $this->baseUrl() . $this->token()->getToken()->url ?? SETTINGS->HOMEPAGEURL;
        header("Location:$url", true, $responseCode);
        exit;
    }

    public function redirectTo($url, int $responseCode = 0): never
    {
        $url = $this->baseUrl() . $url;
        header("location:$url", true, $responseCode);
        exit;
    }

    public function replacePersianhNumbersWithEnglishNumbers(string &$input): void
    {
        $englishNumbers = range(0, 9);
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $input = str_replace($englishNumbers, $persianNumbers, $input);
    }

    public function createPasswordHash(&$password): void
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function passwordVerify($password, $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function getArrayKeysAsString(array $input, &$output, $separator = ','): self
    {
        $keys = (is_array($input[key($input)])) ? array_map('array_keys', $input) : array_keys($input);
        $output = $this->arrayToString($keys, $separator);
        return new static;
    }

    public function getArrayValuesAsString(array $input, &$output, $separator = ','): self
    {
        $output = (is_array($input[key($input)])) ? array_map('array_values', $input) : array_values($input);
        $output = $this->arrayToString($output, $separator);
        return new static;
    }

    public function generateStringOfValuesForInsertQuery(array $input, &$output)
    {
        if (isset($input[0]) && is_array($input[0]))
            foreach ($input as $array) {
                $output = "'" . implode("','", $array) . "'";
            }
        else
            $output = "'" . implode("','", $input) . "'";
    }

    public function replaceArrayValuesWithPlaceholder(array $input): array
    {
        $count = count($input);
        return array_fill(0, $count, '?');
    }

    public function showMessageOrRedirect($message, int $responseCode = 200, string $index = ''): void
    {
        $this->response($message);
        $input = ($index !== '') ? ['message' => [$index => $message, 'responseCode' => $responseCode]] : ['message' => $message, 'responseCode' => $responseCode];
        $this->token()->createToken($input);
        $this->redirect();
    }

    public function isAjax()
    {
        return (isset(Http::requestHeaders()->type) && Http::requestHeaders()->type === 'xhr');
    }

    public function ajaxResponse($response, int $responseCode = 200,)
    {
        exit(json_encode([
            'result' => $response,
            'status' => $responseCode
        ]));
    }

    public function response(string $response = '', $responseCode = 200)
    {
        return $this->isAjax() ? $this->setResponseCode($responseCode)->ajaxResponse($response, $responseCode) : $this;
    }

    public function responseText(string $response, $responseCode = 200)
    {
        http_response_code($responseCode);
        exit($response);
    }

    public function render(string $page, $input = null)
    {
        ob_start([$this, 'minifier']);
        View::render($page, $input);
    }

    public function token()
    {
        return Token::getInstance();
    }

    public function invalidToken(): void
    {
        header($_SERVER['SERVER_PROTOCOL'] . '401 Invalid Token', true, 401);
        http_response_code(401);
        $message = 'توکن نامعتبر!';
        $this->log();
        exit($message);
    }

    public function invalidAccess()
    {
        exit('invalid access!');
    }

    public function invalidRequest()
    {
        exit('!درخواست نا معتبر');
    }

    public function log(): void
    {
        $logs['data'] = $this->token()->getToken();
        $logs = json_decode(json_encode($logs), 1);
        $logs['data']['userIp'] = $_SERVER['REMOTE_ADDR'];
        $logs['data']['responseCode'] = http_response_code();
        error_log(implode(',', $logs['data']));
    }

    public function toJson($input, bool $associative = false): mixed
    {
        return json_decode(json_encode($input), $associative);
    }

    public function toArray($input)
    {
        return json_decode(json_encode($input), true);
    }

    public function baseUrl(): string
    {
        return "http://$_SERVER[HTTP_HOST]/" . SETTINGS->DOMAIN;
    }

    public function getConnection()
    {
        return MysqliConnection::getInstance()->create();
    }

    public function auth()
    {
        return new Auth;
    }

    public function parentObject()
    {
        $trace = debug_backtrace();
        $parent = end($trace)['class'];
        return new $parent;
    }

    public function minifier($buffer)
    {
        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }

    public function checkRequestTimestamp()
    {
        if ($token = $this->token()->getToken())
            if ($token->userIp === $_SERVER['REMOTE_ADDR'] && (microtime(true) - $token->iat) < .2 && !$token->responseCode)
                $this->tooManyRequests();
        return $this;
    }

    public function tooManyRequests()
    {
        $this->response('!درخواست تکراری')->responseText('!درخواست تکراری');
    }

    public function isCustomer()
    {
        $token = $this->token()->getToken();
        if (!isset($token->userId, $token->roleId) || $token->roleId !== 2)
            $this->invalidRequest();
    }
}
