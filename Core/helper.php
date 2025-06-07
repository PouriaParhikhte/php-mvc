<?php

namespace Core;

use Core\Connection\MysqliConnection;
use Core\Helpers\Csrf;
use Core\Helpers\Http;
use Core\Helpers\Prototype;
use Core\Helpers\Token\Token;
use Core\Middlewares\Auth;

class Helper
{
    use Prototype;

    public static function notFound()
    {
        if (self::isAjax()) {
            self::token()->generate(['iat' => .1]);
            self::ajaxResponse('', 404);
        }
        self::render('404');
    }

    public static function setResponseHeader($header, $responseCode)
    {
        header("$_SERVER[SERVER_PROTOCOL] $header", true, $responseCode);
        return new self;
    }

    public static function setResponseCode(int $responseCode)
    {
        http_response_code($responseCode);
        return new self;
    }

    public static function setCache(int $seconds)
    {
        header("Cache-Control: max-age=$seconds");
    }

    public static function noCache()
    {
        header("Pragma: no-cache");
    }

    public static function arrayToString(array $input, $separator = '')
    {
        if (isset($input[0]) && is_array($input[0]))
            foreach ($input as $array) {
                return implode($separator, $array);
            }
        else
            return implode($separator, $input);
    }

    public static function redirect(int $responseCode = 302): never
    {
        Token::$token['iat'] = .1;
        self::token()->generate(Token::$token);
        $url = self::baseUrl() . self::token()->getToken()->url ?? '';
        header("Location:$url", true, $responseCode);
        exit;
    }

    public static function redirectTo($url, int $responseCode = 302): never
    {
        Token::$token['iat'] = .1;
        self::token()->generate(Token::$token);
        $url = self::baseUrl() . $url;
        header("location:$url", true, $responseCode);
        exit;
    }

    public static function replacePersianhNumbersWithEnglishNumbers(string &$input): void
    {
        $englishNumbers = range(0, 9);
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $input = str_replace($englishNumbers, $persianNumbers, $input);
    }

    public static function createPasswordHash(&$password): void
    {
        $password = password_hash($password, PASSWORD_BCRYPT);
    }

    public static function passwordVerify($password, $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function getArrayKeysAsString(array $input, &$output, $separator = ','): self
    {
        $keys = (is_array($input[key($input)])) ? array_map('array_keys', $input) : array_keys($input);
        $output = self::arrayToString($keys, $separator);
        return new static;
    }

    public static function getArrayValuesAsString(array $input, &$output, $separator = ','): self
    {
        $output = (is_array($input[key($input)])) ? array_map('array_values', $input) : array_values($input);
        $output = self::arrayToString($output, $separator);
        return new static;
    }

    public static function generateStringOfValuesForInsertQuery(array $input, &$output)
    {
        if (isset($input[0]) && is_array($input[0]))
            foreach ($input as $array) {
                $output = "'" . implode("','", $array) . "'";
            }
        else
            $output = "'" . implode("','", $input) . "'";
    }

    public static function replaceArrayValuesWithPlaceholder(array $input): array
    {
        $count = count($input);
        return array_fill(0, $count, '?');
    }

    public static function showMessageOrRedirect($message, int $responseCode = 200, string $index = ''): void
    {
        self::response($message);
        Token::$token = ($index !== '') ? ['message' => [$index => $message, 'responseCode' => $responseCode]] : ['message' => $message, 'responseCode' => $responseCode];
        Token::$token['iat'] = '.1';
        self::redirect();
    }

    public static function isAjax()
    {
        return (isset(Http::requestHeaders()->type) && Http::requestHeaders()->type === 'xhr');
    }

    public static function ajaxResponse($response, int $responseCode = 200)
    {
        exit(json_encode([
            'result' => $response,
            'status' => $responseCode
        ]));
    }

    public static function response(string $response = '', $responseCode = 200)
    {
        return self::isAjax() ? self::token()->generate(Token::$token)->setResponseCode($responseCode)->ajaxResponse($response, $responseCode) : (new self);
    }

    public static function responseText(string $response, $responseCode = 200)
    {
        http_response_code($responseCode);
        exit($response);
    }

    public static function render(string $page, $input = null)
    {
        ob_start([self::class, 'minifier']);
        View::render($page, $input);
    }

    public static function token()
    {
        return Token::getInstance();
    }

    public static function invalidToken(): void
    {
        self::setResponseHeader('401 Invalid Token', 401)->setResponseCode(401);
        $message = '!توکن نامعتبر';
        self::log();
        exit($message);
    }

    public static function invalidAccess()
    {
        exit('invalid access!');
    }

    public static function invalidRequest()
    {
        exit('!درخواست نا معتبر');
    }

    public static function log(): void
    {
        $logs['data'] = self::token()->getToken();
        $logs = json_decode(json_encode($logs), 1);
        $logs['data']['userIp'] = $_SERVER['REMOTE_ADDR'];
        $logs['data']['responseCode'] = http_response_code();
        error_log(implode(',', $logs['data']));
    }

    public static function toJson($input, bool $associative = false): mixed
    {
        return json_decode(json_encode($input), $associative);
    }

    public static function toArray($input)
    {
        return json_decode(json_encode($input), true);
    }

    public static function baseUrl(): string
    {
        return "http://$_SERVER[HTTP_HOST]/" . SETTINGS->DOMAIN;
    }

    public static function getConnection()
    {
        return MysqliConnection::getInstance()->create();
    }

    public static function auth()
    {
        return new Auth;
    }

    public static function parentObject()
    {
        $trace = debug_backtrace();
        $parent = end($trace)['class'];
        return new $parent;
    }

    public static function minifier($buffer)
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

    public static function checkRequestTimestamp()
    {
        if ($token = self::token()->getToken()) {
            $responseCodes = [200, 302];
            if (isset($token->userIp) && $token->userIp === $_SERVER['REMOTE_ADDR'] && (microtime(true) - $token->iat) < .2 && (!isset($token->message->responseCode) || !in_array($token->message->responseCode, $responseCodes)))
                self::tooManyRequests();
        }
        return new self;
    }

    public static function tooManyRequests()
    {
        self::token()->generate(['message' => ['tooManyRequests' => '!درخواست تکراری']]);
        self::response('!درخواست تکراری', 400)->responseText('!درخواست تکراری', 400);
    }

    public static function isCustomer()
    {
        $token = self::token()->getToken();
        if (!isset($token->userId, $token->roleId) || $token->roleId !== 2)
            self::invalidRequest();
    }

    public static function postRequestMethod()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST') ? new self : null;
    }

    public static function csrf()
    {
        return Csrf::getInstance();
    }
}
