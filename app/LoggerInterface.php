<?php

namespace banana;

interface LoggerInterface
{
    public function error(string $message, array $data);

    public function warning(string $message, array $data);

    public function debug(string $message, array $data);

}