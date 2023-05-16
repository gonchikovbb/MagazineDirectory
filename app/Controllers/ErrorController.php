<?php

namespace banana\Controllers;

class ErrorController
{
    public function error(): array
    {
        return [
            '../Views/error404.html',
            [],
            false
        ];
    }
}