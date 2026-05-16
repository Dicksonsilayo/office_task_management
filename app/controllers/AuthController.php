<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Auth.php';

class AuthController
{
    public function login()
{
    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // -----------------------------
        // BASIC VALIDATION (BEFORE DB)
        // -----------------------------
        if (empty($email) || empty($password)) {
            $error = "Email and password are required";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
        }

        if (!$error) {

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                Auth::login($user);

                header("Location: index.php?page=dashboard");
                exit;
            }

            $error = "Invalid email or password";
        }
    }

    require __DIR__ . '/../views/auth/login.php';
}
public function logout()
    {
        Auth::logout();

        header("Location: index.php?page=login");
        exit;
    }
}