<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Session {

    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }
}