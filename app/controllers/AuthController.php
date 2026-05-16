<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Auth.php';

class AuthController {

    public function login()
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $userModel = new User();
            $user = $userModel->findByEmail(trim($_POST['email']));

            if ($user && password_verify($_POST['password'], $user['password'])) {

                Auth::login($user);

                header("Location: index.php?page=dashboard");
                exit;
            }

            $error = "Invalid email or password";
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