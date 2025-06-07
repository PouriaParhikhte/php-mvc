<?php

namespace Core;

use ReflectionMethod;

class Route
{
    protected function loadControllerAndAction(string $controller, string $action = '', $args = null): self
    {
        $ctrl = $controller::getInstance($args);
        if ($action !== null)
            $this->callAction($ctrl, $action);
        return $this;
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

    private function loadObject($object): object
    {
        $object = explode(' ', $object);
        return new $object[4];
    }
}
