<?php

require_once __DIR__ . '/Auth.php';

class Guard {

    public static function requireRole($roles)
    {
        Auth::requireLogin();

        $user = Auth::user();

        if (!$user) {
            header("Location: index.php?page=login");
            exit;
        }

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map('strtolower', $roles);

        $userRole = strtolower($user['role'] ?? '');

        if (!in_array($userRole, $roles)) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public static function adminOnly()
    {
        self::requireRole('admin');
    }

    public static function hodOnly()
    {
        self::requireRole('hod');
    }

    public static function adminOrHod()
    {
        self::requireRole(['admin', 'hod']);
    }

    public static function receptionistOnly()
    {
        self::requireRole('receptionist');
    }

    public static function staffOnly()
    {
        self::requireRole('staff');
    }
}