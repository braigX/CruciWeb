<?php

class Session {
    // Automatically start the session when the class is initialized
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Ensure the session is started before accessing it
    private static function ensureSessionStarted() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::ensureSessionStarted();
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        self::ensureSessionStarted();
        return $_SESSION[$key] ?? null;
    }

    public static function has($key) {
        self::ensureSessionStarted();
        return isset($_SESSION[$key]);
    }

    public static function remove($key) {
        self::ensureSessionStarted();
        unset($_SESSION[$key]);
    }

    public static function destroy() {
        self::ensureSessionStarted();
        session_destroy();
        $_SESSION = [];
    }
}
