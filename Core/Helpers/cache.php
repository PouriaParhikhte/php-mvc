<?php

namespace Core\Helpers;

use Core\Crud\InsertOrUpdate;
use Core\Crud\Select;
use Core\Helper;

class Cache extends Select
{
    protected $table = 'cache';

    public function get(string $key)
    {
        $result = $this->select()->where('cacheKey', $key)->where('url', Http::url())->first();
        return isset($result->value) ? json_decode($result->value) : null;
    }

    public function set(string $key, mixed $value)
    {
        $input = ['cacheKey' => $key, 'value' => $value, 'url' => Http::url()];
        InsertOrUpdate::getInstance()->__invoke($this->table)->insertOrUpdate($input)->getResult();
    }

    public function clear()
    {
        $this->sql->query = "TRUNCATE TABLE `$this->table`";
        if (Helper::getConnection()->query($this->sql->query)) {
            $curlhandle = curl_init(SETTINGS->BASEURL);
            curl_exec($curlhandle);
            curl_close($curlhandle);
            clearstatcache();
            Helper::redirect();
        }
    }
}
