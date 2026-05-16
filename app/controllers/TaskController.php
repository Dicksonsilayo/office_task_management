<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Goal.php';
require_once __DIR__ . '/../models/Notification.php';

class TaskController {

    private $taskModel;

    public function __construct()
    {
        $this->taskModel = new Task();
    }

    /*
    |------------------------------------------------
    | OVERDUE TASK CHECK (CRON + DASHBOARD SAFE)
    |------------------------------------------------
    */
    public function checkOverdueTasks()
    {
        $notificationModel = new Notification();

        $tasks = $this->taskModel->getOverdueTasks();

        if (!$tasks) return;

        foreach ($tasks as $task) {

            // prevent duplicate spam notifications (optional improvement later)
            $notificationModel->create(
                $task['assigned_to'],
                $task['id'],
                "Task '{$task['title']}' is overdue!"
            );
        }
    }
    public function show()
{
    Auth::requireLogin();

    $id = $_GET['id'];

    $task = $this->taskModel->getById($id);

    $comments = $this->taskModel->getComments($id);

    $logs = $this->taskModel->getLogs($id);

    require __DIR__ . '/../views/tasks/show.php';
}

public function addComment()
{
    Auth::requireLogin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $taskId = $_POST['task_id'];

        $description = $_POST['description'];

        $userId = Auth::user()['id'];

        $this->taskModel->addComment(
            $taskId,
            $userId,
            $description
        );

        header("Location: index.php?page=task_show&id=" . $taskId);

        exit;
    }
}

public function index()
{
    Auth::requireLogin();

    $taskModel = new Task();

    $tasks = $taskModel->getAllByRole(Auth::user());

    require __DIR__ . '/../views/tasks/index.php';
}

    public function create() {

        Auth::requireLogin();

        $userModel = new User();
        $goalModel = new Goal();

        $users = $userModel->getAll();

        $goals = $goalModel->getAll();

        require __DIR__ . '/../views/tasks/create.php';
    }

    /*
    |--------------------------------------------------------------------------
    | STORE TASK
    |--------------------------------------------------------------------------
    */
    public function store() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [

                'title' => $_POST['title'],

                'assigned_by' => Auth::user()['id'],

                'assigned_to' => $_POST['assigned_to'],

                'goal_id' => $_POST['goal_id']
            ];

            $this->taskModel->create($data);

            header("Location: index.php?page=tasks");

            exit;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TASK STATUS
    |--------------------------------------------------------------------------
    */
    public function updateStatus() {

        Auth::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $taskId = $_POST['task_id'];

            $status = $_POST['status'];

            $this->taskModel->updateStatus($taskId, $status);

            header("Location: index.php?page=tasks");

            exit;
        }
    }
}