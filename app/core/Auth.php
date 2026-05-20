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
    | GET AUTH USER (WITH PIVOT ROLES FIX)
    |--------------------------------------------------------------------------
    */
    public static function user()
    {
        if (!isset($_SESSION['user'])) {
            return null;
        }

        $user = $_SESSION['user'];

        $db = (new Database())->connect();

        $stmt = $db->prepare("
            SELECT r.name
            FROM roles r
            INNER JOIN role_user ru ON ru.role_id = r.id
            WHERE ru.user_id = ?
        ");

        $stmt->bind_param("i", $user['id']);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        // convert roles to simple array
        $roles = [];

        foreach ($result as $r) {
            $roles[] = strtolower($r['name']);
        }

        $user['roles'] = $roles;

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