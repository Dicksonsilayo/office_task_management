<?php

require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Flash.php';

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
    | CREATE FORM
    |--------------------------------------------------------------------------
    */
    public function create() {

        Auth::requireLogin();

        require __DIR__ . '/../views/goals/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE GOAL (FIXED FLASH SYSTEM)
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=goals");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        $errors = [];

        // VALIDATION
        if ($name === '') {
            $errors[] = "Goal name is required";
        } elseif (strlen($name) > 100) {
            $errors[] = "Goal name must not exceed 100 characters";
        }

        if (strlen($description) > 500) {
            $errors[] = "Description must not exceed 500 characters";
        }

        // ERROR HANDLING
        if (!empty($errors)) {

            Flash::set('error', implode("<br>", $errors));

            header("Location: index.php?page=create_goal");
            exit;
        }

        // DATA
        $data = [
            'name' => $name,
            'description' => $description,
            'department_id' => !empty($_POST['department_id']) ? (int) $_POST['department_id'] : null,
            'created_by' => Auth::user()['id']
        ];

        // SAVE
        $this->goalModel->create($data);

        Flash::set('success', "Goal created successfully");

        header("Location: index.php?page=goals");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE GOAL
    |--------------------------------------------------------------------------
    */
    public function delete() {

        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {

            Flash::set('error', "Invalid goal ID");

            header("Location: index.php?page=goals");
            exit;
        }

        $this->goalModel->delete($id);

        Flash::set('success', "Goal deleted successfully");

        header("Location: index.php?page=goals");
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit() {

        Auth::requireLogin();

        $id = (int) ($_GET['id'] ?? 0);

        $goal = $this->goalModel->find($id);

        if (!$goal) {

            Flash::set('error', "Goal not found");

            header("Location: index.php?page=goals");
            exit;
        }

        require __DIR__ . '/../views/goals/edit.php';
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?page=goals");
            exit;
        }

        $this->goalModel->update($_POST);

        Flash::set('success', "Goal updated successfully");

        header("Location: index.php?page=goals");
        exit;
    }
}