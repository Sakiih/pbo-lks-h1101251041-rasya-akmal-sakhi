<?php

namespace App\Core;

use ReflectionClass;
use Exception;

class Container
{
    private array $bindings = [];

    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make(string $abstract)
    {
        if (isset($this->bindings[$abstract])) {

            $concrete = $this->bindings[$abstract];

            if ($concrete instanceof \Closure) {
                return $concrete($this);
            }

            if (is_object($concrete)) {
                return $concrete;
            }

            return $this->build($concrete);
        }

        return $this->build($abstract);
    }

    private function build(string $class)
    {
        $reflection = new ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new Exception(
                "Class {$class} tidak dapat dibuat."
            );
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $parameter) {

            $type = $parameter->getType();

            if ($type === null || $type->isBuiltin()) {
                throw new Exception(
                    "Dependency tidak dapat diselesaikan."
                );
            }

            $dependencies[] = $this->make($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}