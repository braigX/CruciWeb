<?php

class Logger {
    private static $logFile = __DIR__ . '/../storage/logs/app.log';

    public static function log($message, $type = 'INFO') {

        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] [$type] $message" . PHP_EOL;

        if (!file_exists(dirname(self::$logFile))) {
            mkdir(dirname(self::$logFile), 0777, true);
        }

        file_put_contents(self::$logFile, $logEntry, FILE_APPEND);
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }

    public static function info($message) {
        self::log($message, 'INFO');
    }
}
