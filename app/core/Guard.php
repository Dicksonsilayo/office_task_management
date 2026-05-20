<?php

require_once __DIR__ . '/Auth.php';

class Guard
{
    private static function roles()
    {
        $user = Auth::user();
        return $user['roles'] ?? [];
    }

    public static function auth()
    {
        if (!Auth::check()) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    public static function adminOnly()
    {
        self::auth();

        if (!in_array('admin', self::roles())) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public static function adminOrHod()
    {
        self::auth();

        if (
            !in_array('admin', self::roles()) &&
            !in_array('hod', self::roles())
        ) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public static function receptionistOnly()
    {
        self::auth();

        if (!in_array('receptionist', self::roles())) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }
    public static function adminOrReceptionist()
{
    self::auth();

    if (
        !in_array('admin', self::roles()) &&
        !in_array('receptionist', self::roles())
    ) {
        header("Location: index.php?page=dashboard");
        exit;
    }
}
}