<?php

namespace Core;

use ReflectionMethod;

class Route
{
    public function loadControllerAndAction(string $controller, string $action, $args = null): void
    {
        $ctrl = new $controller($args);
        $this->callAction($ctrl, $action);
    }

    private function callAction($ctrl, $action): void
    {
        $this->getMethodParameters($ctrl, $action, $parameters);
        if (!empty($parameters))
            $classes = array_map([$this, 'loadObject'], $parameters);
        call_user_func_array([$ctrl, $action], $classes ?? []);
    }

    private function getMethodParameters($controller, $action, &$parameters): void
    {
        $reflectionMethod = new ReflectionMethod($controller, $action);
        $parameters = $reflectionMethod->getParameters();
    }

    private function loadObject($object)
    {
        $object = explode(' ', $object)[4];
        return new $object;
    }
}
