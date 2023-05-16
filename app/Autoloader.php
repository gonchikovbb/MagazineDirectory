<?php

class Autoloader
{
    public static function register(string $appRoot): void
    {
        spl_autoload_register(function ($class) use ($appRoot){

            $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

            $path = preg_replace('#^banana#', $appRoot, $path);

            if (file_exists($path)) {
                require_once $path;
                return true;
            }

            return false;
        });
    }
}