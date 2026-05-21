<?php

class Flash
{
    public static function set($key, $message)
    {
        $_SESSION[$key] = $message;
    }

    public static function get($key)
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        $message = $_SESSION[$key];
        unset($_SESSION[$key]); // AUTO DELETE (IMPORTANT FIX)

        return $message;
    }

    public static function getErrors()
    {
        if (!isset($_SESSION['errors'])) {
            return [];
        }

        $errors = $_SESSION['errors'];
        unset($_SESSION['errors']);

        return $errors;
    }
}