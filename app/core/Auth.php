<?php

require_once __DIR__ . '/../models/User.php';

class Auth {

    public static function user()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $userModel = new User();
        return $userModel->find($_SESSION['user_id']);
    }

    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
    }

    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();
    }
}