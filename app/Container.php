<?php

namespace banana;

use banana\Exception\ExceptionContainer;

class Container
{
    private array $services = [];

    public function __construct(array $data)
    {
        $this->services = $data;
    }

    public function set(string $name, mixed $value): void
    {
        $this->services[$name] = $value;
    }


    /**
     * @throws ExceptionContainer
     */
    public function get(string $name): mixed
    {
        if (!isset($this->services[$name]))
        {
            if (class_exists($name)) {
                return new $name();
            }

            throw new ExceptionContainer("Class named '{$name}' does not exist.");
        }

        if (is_callable($this->services[$name])) {
            return $this->services[$name]($this);
        }

        return $this->services[$name];
    }
}