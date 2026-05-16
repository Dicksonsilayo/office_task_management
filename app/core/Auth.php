<?php

require_once __DIR__ . '/../models/User.php';

class Auth {

    public static function user()
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        static $cache = null;

        if ($cache !== null) return $cache;

        $userModel = new User();
        $cache = $userModel->find($_SESSION['user_id']);

        return $cache;
    }

    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    public static function login($user)
    {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
    }

    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }
}