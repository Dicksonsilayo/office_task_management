<?php

require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../core/Auth.php';

class GoalController {

    private $goalModel;

    public function __construct() {
        $this->goalModel = new Goal();
    }

    /*
    |--------------------------------------------------------------------------
    | LIST GOALS
    |--------------------------------------------------------------------------
    */
    public function index() {

        Auth::requireLogin();

        $goals = $this->goalModel->getAll();

        require __DIR__ . '/../views/goals/index.php';
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create() {

        Auth::requireLogin();

        require __DIR__ . '/../views/goals/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE GOAL
    |--------------------------------------------------------------------------
    */public function store()
{
    Auth::requireLogin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = trim($_POST['name']);
        $description = trim($_POST['description'] ?? '');

        // -------------------------
        // VALIDATION ERRORS ARRAY
        // -------------------------
        $errors = [];

        // NAME VALIDATION
        if (empty($name)) {
            $errors[] = "Goal name is required";
        } elseif (strlen($name) > 100) {
            $errors[] = "Goal name must not exceed 100 characters";
        }

        // DESCRIPTION VALIDATION
        if (strlen($description) > 500) {
            $errors[] = "Description must not exceed 500 characters";
        }

        // -------------------------
        // IF ERRORS FOUND
        // -------------------------
        if (!empty($errors)) {

            $_SESSION['error'] = implode("<br>", $errors);

            header("Location: index.php?page=create_goal");
            exit;
        }

        // -------------------------
        // INSERT DATA
        // -------------------------
        $data = [
            'name' => $name,
            'description' => $description,
            'department_id' => $_POST['department_id'] ?? null,
            'created_by' => Auth::user()['id']
        ];

        $this->goalModel->create($data);

        $_SESSION['success'] = "Goal created successfully";

        header("Location: index.php?page=goals");
        exit;
    }
}
    /*
    |--------------------------------------------------------------------------
    | DELETE GOAL
    |--------------------------------------------------------------------------
    */
    public function delete() {

    Auth::requireLogin();

    $id = $_GET['id'];

    $this->goalModel->delete($id);

    header("Location: index.php?page=goals");
    exit;
}
public function edit() {

    Auth::requireLogin();

    $id = $_GET['id'];

    $goal = $this->goalModel->find($id);

    require __DIR__ . '/../views/goals/edit.php';
}
public function update() {

    Auth::requireLogin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $this->goalModel->update($_POST);

        header("Location: index.php?page=goals");
        exit;
    }
}
}