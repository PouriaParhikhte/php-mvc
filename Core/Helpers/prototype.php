<?php

namespace Core\Helpers;

trait Prototype
{
    private static $instance = [];

    public static function getInstance(mixed $input = []): mixed
    {
        $class = static::class;
        if (!isset(static::$instance[$class]))
            static::$instance[$class] = new static($input);
        return static::$instance[$class];
    }
}
