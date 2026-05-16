<?php

require_once __DIR__ . '/Auth.php';

class Guard
{
    /*
    |--------------------------------------------------------------------------
    | BASE CHECK (CORE LOGIC)
    |--------------------------------------------------------------------------
    */
    private static function check(array $roles = [])
    {
        Auth::requireLogin();

        $user = Auth::user();

        if (!$user) {
            header("Location: index.php?page=login");
            exit;
        }

        $role = strtolower($user['role'] ?? '');

        $roles = array_map('strtolower', $roles);

        if (!in_array($role, $roles)) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | AUTH ONLY (ANY LOGGED USER)
    |--------------------------------------------------------------------------
    */
    public static function auth()
    {
        Auth::requireLogin();
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */
    public static function adminOnly()
    {
        self::check(['admin']);
    }

    /*
    |--------------------------------------------------------------------------
    | HOD ONLY
    |--------------------------------------------------------------------------
    */
    public static function hodOnly()
    {
        self::check(['hod']);
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN OR HOD
    |--------------------------------------------------------------------------
    */
    public static function adminOrHod()
    {
        self::check(['admin', 'hod']);
    }

    /*
    |--------------------------------------------------------------------------
    | RECEPTIONIST ONLY
    |--------------------------------------------------------------------------
    */
    public static function receptionistOnly()
    {
        self::check(['receptionist']);
    }

    /*
    |--------------------------------------------------------------------------
    | STAFF ONLY
    |--------------------------------------------------------------------------
    */
    public static function staffOnly()
    {
        self::check(['staff']);
    }

    /*
    |--------------------------------------------------------------------------
    | OPTIONAL DEBUG HELP (REMOVE LATER IF NEEDED)
    |--------------------------------------------------------------------------
    */
    public static function debugRole()
    {
        $user = Auth::user();
        echo "<pre>";
        print_r($user);
        echo "</pre>";
        exit;
    }
}