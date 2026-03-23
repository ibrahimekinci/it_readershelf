<?php

class Logger
{
    private static $logFile = __DIR__ . '/../app.log';

    public static function error(string $message, ?Throwable $exception = null): void
    {
        self::writeLog("ERROR", $message, $exception);
    }

    public static function info(string $message): void
    {
        self::writeLog("INFO", $message);
    }

    private static function writeLog(string $level, string $message, ?Throwable $exception = null): void
    {
        $timestamp = date('Y-m-d H:i:s');

        $logMessage = "[$timestamp] $level: $message";
        if ($exception) {
            $logMessage .= " | Exception: " . $exception->getMessage() . " | File: " . $exception->getFile() . ":" . $exception->getLine();
        }
        $logMessage .= PHP_EOL;

        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
}