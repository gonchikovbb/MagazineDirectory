<?php
namespace banana;

class FileLogger implements LoggerInterface
{
    public function error(string $message, array $data): void
    {
        $log = json_encode($data);
        $log = "{$message}: \n" . $log . "\n";
        file_put_contents(__DIR__ . '/Logs/error.php', $log . PHP_EOL, FILE_APPEND);
    }

    public function warning(string $message, array $data): void
    {
        $log = json_encode($data);
        $log = "{$message}: \n" . $log . "\n";
        file_put_contents(__DIR__ . '/Logs/warning.php', $log . PHP_EOL, FILE_APPEND);
    }

    public function debug(string $message, array $data): void
    {
        $log = json_encode($data);
        $log = "{$message}: \n" . $log . "\n";
        file_put_contents(__DIR__ . '/Logs/debug.php', $log . PHP_EOL, FILE_APPEND);
    }
}