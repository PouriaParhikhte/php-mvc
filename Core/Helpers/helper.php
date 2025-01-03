<?php

namespace Core\Helpers;

use Core\Helpers\Configs;
use Core\Helpers\Token\CreateToken;
use Core\Helpers\Token\fetchValueFromToken;
use Core\Helpers\Token\GetPreviousToken;

class Helper
{
    public static function notFound(): never
    {
        header('HTTP/1.1 404 Not Found', true, 404);
        exit('<center><h1>404</h1><h2>Not Found!</h2></center>');
    }

    public static function log(array $input = null): void
    {
        $logs = [
            'userIp' => $_SERVER['REMOTE_ADDR'],
            'url' => $_GET['url'] ?? Configs::homePageUrl(),
            'responseCode' => http_response_code()
        ];
        if (isset($input))
            $logs = array_merge($logs, $input);
        error_log(implode(',', $logs));
    }

    public static function arrayToString(array $input, $separator = ''): string
    {
        if ($input)
            return implode($separator, $input);
    }

    public static function replaceArrayValuesWithPlaceholder(array $input): array
    {
        $count = count($input);
        return array_fill(0, $count, '?');
    }

    public static function getRequestMethod(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    public static function postRequestMethod(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    public static function redirect($message = null, int $responseCode = 302, $index = 'message'): never
    {
        if ($message !== '')
            CreateToken::getInstance([$index => $message]);
        $url = Configs::baseUrl() . fetchValueFromToken::getInstance()->fetch('url');
        header("location:$url", true, $responseCode);
        exit;
    }

    public static function redirectTo($url, $message = null, $responseCode = 302, $index = 'message'): never
    {
        if ($message !== '')
            CreateToken::getInstance([$index => $message]);
        $url = Configs::baseUrl() . $url;
        header("location:$url", true, $responseCode);
        exit;
    }

    public static function replacePersianhNumbersWithEnglishNumbers(string &$input): void
    {
        $englishNumbers = range(0, 9);
        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $input = str_replace($englishNumbers, $persianNumbers, $input);
    }

    public static function minifier($page)
    {
        $file = file($page);
        $file = array_map('trim', $file);
        $file = implode('', $file);
        $file = str_replace('<?php', '<?php ', $file);
        $page = substr($page, 0, -4);
        $page .= '.min.php';
        file_put_contents($page, $file);
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
        $keys = array_keys($input);
        $output = self::arrayToString($keys, $separator);
        return new static;
    }

    public static function getArrayValuesAsString(array $input, &$output, $separator = ','): self
    {
        $placeholder = self::replaceArrayValuesWithPlaceholder($input);
        $output = self::arrayToString($placeholder, $separator);
        return new static;
    }

    public static function sendMessageOrRedirect($message, $responseCode, $index = 'message'): never
    {
        http_response_code($responseCode);
        $headers = getallheaders();
        if (isset($headers['type']) && $headers['type'] === 'xhr') {
            // self::bearerAuthorizationHeader();
            exit($message);
        }
        self::redirect($message, $responseCode, $index);
    }

    public static function invalidToken()
    {
        http_response_code(401);
        $message = 'توکن نامعتبر!';
        Helper::log(['message' => $message]);
        exit($message);
    }

    public static function getToken($token)
    {
        if ($token) {
            $jwt = explode('.', $token, 3);
            if (count($jwt) < 3)
                self::invalidToken();
            return $jwt[1];
        }
    }

    public static function decodeJwt()
    {
        CreateToken::getInstance();
    }

    public static function createArrayFromJwt($token): array
    {
        $jwt = explode('.', $token, 3);
        if (count($jwt) < 3)
            self::invalidToken();
        return $jwt;
    }

    public static function decodePayload($payload, int $associative = 0): mixed
    {
        $payload = base64_decode($payload[1]);
        return json_decode($payload, $associative);
    }

    public static function getUrlWithoutQueryString()
    {
        return substr($_GET['url'], 0, -strpos(strrev($_GET['url']), '/') - 1);
    }
}
