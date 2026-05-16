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
    */
    public function store() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? null,
                'department_id' => $_POST['department_id'] ?? null,
                'created_by' => Auth::user()['id']
            ];

            $this->goalModel->create($data);

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