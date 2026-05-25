<?php

require_once __DIR__ . '/../configs/database.php';

class Auth
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN USER
    |--------------------------------------------------------------------------
    */
    public static function login($user)
    {
        session_start();
        $_SESSION['user'] = $user;
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT USER
    |--------------------------------------------------------------------------
    */
    public static function logout()
    {
        session_start();
        session_destroy();
    }

    /*
    |--------------------------------------------------------------------------
    | GET USER (WITH ROLES FROM DB - FIXED)
    |--------------------------------------------------------------------------
    */public static function user()
{
    if (!isset($_SESSION['user'])) {
        return null;
    }

    $user = $_SESSION['user'];

    // ensure roles come from DB column OR session
    if (!isset($user['roles'])) {
        $user['roles'] = '';
    }

    // normalize roles into array
    if (is_string($user['roles'])) {
        $user['roles'] = array_filter(
            array_map('trim', explode(',', strtolower($user['roles'])))
        );
    }

    return $user;
}
    /*
    |--------------------------------------------------------------------------
    | CHECK LOGIN
    |--------------------------------------------------------------------------
    */
    public static function check()
    {
        return isset($_SESSION['user']);
    }

    public static function isLoggedIn()
    {
        return self::check();
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE CHECKER
    |--------------------------------------------------------------------------
    */
    public static function hasRole($role)
    {
        $user = self::user();

        if (!$user || empty($user['roles'])) {
            return false;
        }

        return in_array(strtolower($role), $user['roles']);
    }

    public static function isAdmin()
    {
        return self::hasRole('admin');
    }

    public static function isHead()
    {
        return self::hasRole('head');
    }

    public static function isReceptionist()
    {
        return self::hasRole('receptionist');
    }

    /*
    |--------------------------------------------------------------------------
    | REQUIRE LOGIN
    |--------------------------------------------------------------------------
    */
    public static function requireLogin()
    {
        if (!self::check()) {
            header("Location: index.php?page=login");
            exit;
        }
    }
}