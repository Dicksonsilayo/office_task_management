<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../configs/database.php';
require_once __DIR__ . '/../core/Auth.php';

class UserController {

    private $userModel;
    private $db;

    public function __construct() {

        $this->userModel = new User();

        $this->db = (new Database())->connect();
    }

    /*
    |--------------------------------------------------------------------------
    | USER LIST
    |--------------------------------------------------------------------------
    */
    public function index() {

        $users = $this->userModel->getAll();

        require __DIR__ . '/../views/user/index.php';
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create() {

        $departments = $this->db
            ->query("SELECT * FROM departments")
            ->fetch_all(MYSQLI_ASSOC);

        $roles = $this->db
            ->query("SELECT * FROM roles")
            ->fetch_all(MYSQLI_ASSOC);

        // IMPORTANT
        // Prevent logged-in user data leaking into create form
        $user = null;

        require __DIR__ . '/../views/user/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE USER
    |--------------------------------------------------------------------------
    */
    public function store() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // PASSWORD VALIDATION
            if ($_POST['password'] !== $_POST['confirm_password']) {

                $_SESSION['error'] = "Passwords do not match";

                header("Location: index.php?page=create_user");
                exit;
            }

            $this->userModel->create($_POST);

            $_SESSION['success'] = "User created successfully";

            header("Location: index.php?page=users");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT USER
    |--------------------------------------------------------------------------
    */public function edit()
{
    Auth::requireLogin();

    if (!isset($_GET['id'])) {
        die("User ID missing");
    }

    $userModel = new User();

    // IMPORTANT: DO NOT USE Auth::user() HERE
    $editUser = $userModel->find($_GET['id']);

    if (!$editUser) {
        die("User not found");
    }

    require __DIR__ . '/../views/user/edit.php';
}
    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */
    public function update()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // PASSWORD CHECK
            if (
                !empty($_POST['password']) &&
                $_POST['password'] !== $_POST['confirm_password']
            ) {

                $_SESSION['error'] = "Passwords do not match";

                header("Location: index.php?page=edit_user&id=" . $_POST['id']);
                exit;
            }

            $this->userModel->update([
                'id' => $_POST['id'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'] ?? '',
                'department_id' => $_POST['department_id'] ?? null,
                'role_id' => $_POST['role_id'] ?? null
            ]);

            $_SESSION['success'] = "User updated successfully";

            header("Location: index.php?page=users");
            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function delete() {

        if (!isset($_GET['id'])) {
            die("User ID missing");
        }

        $id = $_GET['id'];

        $this->userModel->delete($id);

        $_SESSION['success'] = "User deleted successfully";

        header("Location: index.php?page=users");
        exit;
    }
}