<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
require_once __DIR__ . '/Auth.php';

class Middleware {

    public static function auth() {

        if (!Auth::isLoggedIn()) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    public static function headOnly() {

        if (!Auth::isHead()) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }

    public static function receptionistOnly() {

        if (!Auth::isReceptionist()) {
            header("Location: index.php?page=dashboard");
            exit;
        }
    }
}